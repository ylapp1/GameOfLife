<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Output\Helpers\FfmpegHelper;
use Ulrichsg\Getopt;

/**
 * Creates a video with sound from the boards.
 */
class VideoOutput extends ImageOutput
{
    /**
     * Fill percentage of each board during the simulation
     * Is used to generate a beep sound for each frame
     *
     * @var array $fillPercentages
     */
    private $fillPercentages;

    /**
     * Frames per second
     *
     * @var int $fps
     */
    private $fps;

    /**
     * File paths of the frame images
     *
     * @var array $frames
     */
    private $frames;

    /**
     * Indicates whether the video shall have sound.
     *
     * True: Video will have beep sounds
     * False: Video will have no sound
     *
     * @var bool $hasSound
     */
    private $hasSound;

    /**
     * The ffmpeg helper
     *
     * @var FfmpegHelper $ffmpegHelper
     */
    private $ffmpegHelper;


    /**
     * VideoOutput constructor.
     *
     * @throws \Exception The exception when the ffmpeg helper could not be constructed
     */
    public function __construct()
    {
        parent::__construct("video", "/tmp/Frames");

        $this->fillPercentages = array();
        $this->frames = array();
        $this->hasSound = false;

        $this->ffmpegHelper = new FfmpegHelper(PHP_OS);
    }


    /**
     * Returns the fill percentage list of this video output.
     *
     * @return array Fill percentage list
     */
    public function fillPercentages(): array
    {
        return $this->fillPercentages;
    }

    /**
     * Sets the fill percentage list of this video output.
     *
     * @param array $_fillPercentages Fill percentage list
     */
    public function setFillPercentages(array $_fillPercentages)
    {
        $this->fillPercentages = $_fillPercentages;
    }

    /**
     * Returns the frames per second of this video output.
     *
     * @return int Frames per second of this video output
     */
    public function fps(): int
    {
        return $this->fps;
    }

    /**
     * Sets the frames per second of this video output.
     *
     * @param int $_fps Frames per second of this video output
     */
    public function setFps(int $_fps)
    {
        $this->fps = $_fps;
    }

    /**
     * Returns the frame path list of this video output.
     *
     * @return array Frame path list
     */
    public function frames(): array
    {
        return $this->frames;
    }

    /**
     * Sets the frame path list of this video output.
     *
     * @param array $_frames Frame path list
     */
    public function setFrames(array $_frames)
    {
        $this->frames = $_frames;
    }

    /**
     * Returns whether this video output will add sound to the video file.
     *
     * @return bool Indicates whether the video has sound or not
     */
    public function hasSound(): bool
    {
        return $this->hasSound;
    }

    /**
     * Sets whether this video output will add sound to the video file.
     *
     * @param bool $hasSound Indicates whether the video has sound or not
     */
    public function setHasSound(bool $hasSound)
    {
        $this->hasSound = $hasSound;
    }

    /**
     * Returns the ffmpeg helper.
     *
     * @return FfmpegHelper The ffmpeg helper
     */
    public function ffmpegHelper()
    {
        return $this->ffmpegHelper;
    }

    /**
     * Sets the ffmpeg helper.
     *
     * @param FfmpegHelper $_ffmpegHelper The ffmpeg helper
     */
    public function setFfmpegHelper(FfmpegHelper $_ffmpegHelper)
    {
        $this->ffmpegHelper = $_ffmpegHelper;
    }


    /**
     * Adds VideoOutputs specific options to an option list.
     *
     * @param Getopt $_options The option list to which the options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(array(
                array(null, "videoOutputFPS", Getopt::REQUIRED_ARGUMENT, "VideoOutput - Frames per second of videos"),
                array(null, "videoOutputAddSound", Getopt::NO_ARGUMENT, "VideoOutput - Add sound to the video"))
        );
        parent::addOptions($_options);
    }

    /**
     * Starts the output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     *
     * @throws \Exception
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting video output ...\n\n";

        try
        {
            $this->fileSystemHandler->createDirectory($this->baseOutputDirectory . "/Video");
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }

        try
        {
            $this->fileSystemHandler->createDirectory($this->baseOutputDirectory . "/tmp/Audio");
        }
        catch (\Exception $_exception)
        {
            // Remove the files from the directory
            $this->fileSystemHandler->deleteFilesInDirectory($this->baseOutputDirectory . "/tmp/Audio", true);
        }


        // fetch options
        if ($_options->getOption("videoOutputFPS") !== null)
        {
            $this->fps = (int)$_options->getOption("videoOutputFPS");
        }
        else $this->fps = 15;

        if ($_options->getOption("videoOutputAddSound") !== null) $this->hasSound = true;
        else $this->hasSound = false;
    }

    /**
     * Creates PNG files which will later be combined to a video.
     *
     * @param Board $_board The board from which the ImageCreator will create an image
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);

        $image = $this->imageCreator->createImage($_board);

        $fileName = $_board->gameStep() . ".png";
        $filePath = $this->imageOutputDirectory . "/" . $fileName;

        imagepng($image, $filePath);
        unset($image);

        $this->frames[] = $filePath;
        $this->fillPercentages[] = $_board->getFillpercentage();
    }

    /**
     * Creates the video file from the frames and adds a sound per game step.
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     *
     * @throws \Exception The exception when the video file generation fails
     */
    public function finishOutput(String $_simulationEndReason)
    {
        parent::finishOutput($_simulationEndReason);
        echo "\nStarting video creation ...\n";

        $this->generateVideoFile();

        unset($this->imageCreator);
        $this->fileSystemHandler->deleteDirectory($this->baseOutputDirectory . "/tmp", true);
    }

    /**
     * Generates the video file.
     *
     * @throws \Exception The exception when the frames folder is empty or the ffmpeg command returns an error
     */
    private function generateVideoFile()
    {
        if (count($this->frames) == 0)
        {
            throw new \Exception("No frames in frames folder found.");
        }

        if ($this->hasSound == true)
        {
            $audioListFilePath = $this->generateAudioFiles();

            // Create single sound from sound frames
            $this->ffmpegHelper->addOption("-f concat");
            $this->ffmpegHelper->addOption("-safe 0");
            $this->ffmpegHelper->addOption("-i \"" . $audioListFilePath . "\"");
        }

        echo "\nGenerating video file ...";

        // Create video from image frames
        $this->ffmpegHelper->addOption("-framerate " . $this->fps);

        // Input images
        $this->ffmpegHelper->addOption("-i \"" . $this->baseOutputDirectory . "/tmp/Frames/%d.png\"");
        $this->ffmpegHelper->addOption("-pix_fmt yuv420p");
        $this->ffmpegHelper->addOption("-vcodec mpeg4");

        if (stristr(PHP_OS, "linux"))
        {
            // This option is necessary to avoid an error on Linux
            $this->ffmpegHelper->addOption("-strict -2");
        }

        $fileName = "Game_" . $this->getNewGameId("Video") . ".mp4";

        // Save video in output folder
        $this->ffmpegHelper->executeCommand( $this->baseOutputDirectory . "/Video/" . $fileName);

        echo "\nVideo creation complete!\n\n";
    }

    /**
     * Generates the audio files for each frame and writes the paths to the files to a temporary file.
     *
     * @return String The path to the temporary audio list file
     *
     * @throws \Exception The exception when the ffmpeg command returned an error
     */
    private function generateAudioFiles(): String
    {
        $amountFrames = count($this->frames);
        $secondsPerFrame = floatval(ceil(1000 / $this->fps) / 1000);
        $audioListFilePath = $this->baseOutputDirectory . "/tmp/Audio/list.txt";

        $this->ffmpegHelper->resetOptions();

        try
        {
            $this->fileSystemHandler->deleteFile($audioListFilePath);
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }

        for ($i = 0; $i < count($this->frames); $i++)
        {
            echo "\rGenerating audio ... " . ($i + 1) . "/" . $amountFrames;
            $outputPath = "tmp/Audio/" . $i . ".wav";

            // Generate beep sound based on the fill percentage
            $this->ffmpegHelper->addOption("-f lavfi");
            $this->ffmpegHelper->addOption("-i \"sine=frequency=" . (10000 * $this->fillPercentages[$i]) . ":duration=1\"");
            $this->ffmpegHelper->addOption("-t " . $secondsPerFrame);

            $this->ffmpegHelper->executeCommand($this->baseOutputDirectory . "/" . $outputPath);
            $this->fileSystemHandler->writeFile($audioListFilePath, "file '" . $i . ".wav'\r\n", true);

            $this->ffmpegHelper->resetOptions();
        }

        return $audioListFilePath;
    }
}
