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
     */
    public function __construct()
    {
        $outputDirectory = $this->baseOutputDirectory . "tmp/Frames";
        parent::__construct("video", $outputDirectory);

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
        parent::addOptions($_options);
        $_options->addOptions(array(
            array(null, "videoOutputFPS", Getopt::REQUIRED_ARGUMENT, "Frames per second of videos"),
            array(null, "videoOutputAddSound", Getopt::NO_ARGUMENT, "Add sound to the video"))
        );
    }

    /**
     * Starts the output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting video output ...\n\n";

        $this->fileSystemHandler->createDirectory($this->baseOutputDirectory . "/Video");
        $this->fileSystemHandler->createDirectory($this->baseOutputDirectory . "/tmp/Audio");

        // fetch options
        $fps = $_options->getOption("videoOutputFPS");
        if ($fps !== null) $this->fps = (int)$fps;
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
        $this->frames[] = $this->imageCreator->createImage($_board, "video");
        $this->fillPercentages[] = $_board->getFillpercentage();
    }

    /**
     * Creates the video file from the frames and adds a sound per game step.
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     */
    public function finishOutput(String $_simulationEndReason)
    {
        parent::finishOutput($_simulationEndReason);
        echo "\nStarting video creation ...\n";

        // Initialize ffmpeg helper
        if (! $this->ffmpegHelper->binaryPath()) echo "Error: Ffmpeg binary not found\n";
        else $this->generateVideoFile();

        unset($this->imageCreator);
        $this->fileSystemHandler->deleteDirectory($this->baseOutputDirectory . "/tmp", true);
    }

    private function generateVideoFile()
    {
        $ffmpegOutputDirectory = str_replace("\\", "/", $this->baseOutputDirectory);

        if (count($this->frames) == 0)
        {
            echo "Error: No frames in frames folder found!\n";
            return;
        }

        // generate Audio files for each frame
        if ($this->hasSound == true)
        {
            $amountFrames = count($this->frames);
            $secondsPerFrame = floatval(ceil(1000 / $this->fps) / 1000);
            $audioListPath = $this->baseOutputDirectory . "tmp/Audio/list.txt";

            for ($i = 0; $i < count($this->frames); $i++)
            {
                echo "\rGenerating audio ... " . ($i + 1) . "/" . $amountFrames;
                $outputPath = "tmp/Audio/" . $i . ".wav";

                // Generate random beep sound
                $this->ffmpegHelper->resetOptions();
                $this->ffmpegHelper->addOption("-f lavfi");
                $this->ffmpegHelper->addOption("-i \"sine=frequency=" . (10000 * $this->fillPercentages[$i]) . ":duration=1\"");
                $this->ffmpegHelper->addOption("-t " . $secondsPerFrame);

                $error = $this->ffmpegHelper->executeCommand($ffmpegOutputDirectory . $outputPath);

                if ($error)
                {
                    echo "\nError while creating the audio files. Is ffmpeg installed?\n";
                    return;
                }

                file_put_contents($audioListPath, "file '" . $i . ".wav'\r\n", FILE_APPEND);
            }
        }

        echo "\nGenerating video file ...";

        // Create video
        $this->ffmpegHelper->resetOptions();

        if ($this->hasSound == true)
        {
            // Create single sound from sound frames
            $this->ffmpegHelper->addOption("-f concat");
            $this->ffmpegHelper->addOption("-safe 0");
            $this->ffmpegHelper->addOption("-i \"" . $ffmpegOutputDirectory . "tmp/Audio/list.txt\"");
        }

        // Create video from image frames
        $this->ffmpegHelper->addOption("-framerate " . $this->fps);
        $this->ffmpegHelper->addOption("-i \"" . $ffmpegOutputDirectory . "tmp/Frames/%d.png\""); // Input Images
        $this->ffmpegHelper->addOption("-pix_fmt yuv420p");

        $fileName = "Game_" . $this->getNewGameId("Video") . ".mp4";

        // Save video in output folder
        $error = $this->ffmpegHelper->executeCommand( $this->baseOutputDirectory . "Video/" . $fileName);

        if ($error)
        {
            echo "\nError while creating the video file. Is ffmpeg installed?\n";
            return;
        }

        echo "\nVideo creation complete!\n\n";
    }
}
