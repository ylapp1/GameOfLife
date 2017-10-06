<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Output\Helpers\ColorSelector;
use Output\Helpers\FfmpegHelper;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Creates a video with sound from the boards
 *
 * @package Output
 */
class VideoOutput extends ImageOutput
{
    private $fillPercentages = array();
    private $fps;
    private $frames = array();
    private $hasSound;


    /**
     * VideoOutput constructor
     */
    public function __construct()
    {
        $outputDirectory = $this->outputDirectory . "tmp/Frames";
        parent::__construct("video", $outputDirectory);

        $this->hasSound = false;
    }


    /**
     * Returns the fill percentage list of this video output
     *
     * @return array    Fill percentage list
     */
    public function fillPercentages(): array
    {
        return $this->fillPercentages;
    }

    /**
     * Sets the fill percentage list of this video output
     *
     * @param array $_fillPercentages   Fill percentage list
     */
    public function setFillPercentages(array $_fillPercentages)
    {
        $this->fillPercentages = $_fillPercentages;
    }

    /**
     * Returns the frames per second of this video output
     *
     * @return int     Frames per second of this video output
     */
    public function fps(): int
    {
        return $this->fps;
    }

    /**
     * Sets the frames per second of this video output
     *
     * @param int $_fps    Frames per second of this video output
     */
    public function setFps(int $_fps)
    {
        $this->fps = $_fps;
    }

    /**
     * Returns the frame path list of this video output
     *
     * @return array    Frame path list
     */
    public function frames(): array
    {
        return $this->frames;
    }

    /**
     * Sets the frame path list of this video output
     *
     * @param array $_frames    Frame path list
     */
    public function setFrames(array $_frames)
    {
        $this->frames = $_frames;
    }

    /**
     * Returns whether this video output will add sound to the video file
     *
     * @return bool    Indicates whether the video has sound or not
     */
    public function hasSound(): bool
    {
        return $this->hasSound;
    }

    /**
     * Sets whether this video output will add sound to the video file
     *
     * @param bool $hasSound    Indicates whether the video has sound or not
     */
    public function setHasSound(bool $hasSound)
    {
        $this->hasSound = $hasSound;
    }


    /**
     * Adds VideoOutputs specific options to an option list
     *
     * @param Getopt $_options      The option list to which the options are added
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
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting video output ...\n\n";

        $this->fileSystemHandler->createDirectory($this->outputDirectory . "/Video");
        $this->fileSystemHandler->createDirectory($this->outputDirectory . "/tmp/Audio");

        // fetch options
        $fps = $_options->getOption("videoOutputFPS");
        if ($fps !== null) $this->fps = (int)$fps;
        else $this->fps = 15;

        if ($_options->getOption("videoOutputAddSound") !== null) $this->hasSound = true;
        else $this->hasSound = false;
    }

    /**
     * Creates PNG files which will later be combined to a video
     *
     * @param Board $_board     The board from which the ImageCreator will create an image
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->frames[] = $this->imageCreator->createImage($_board, "video");
        $this->fillPercentages[] = $_board->getFillpercentage();
    }

    /**
     * Creates the video file from the frames and adds a sound per game step
     */
    public function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n";
        echo "\nStarting video creation ...\n";

        $fileName = "Game_" . $this->getNewGameId("Video") . ".mp4";

        // Initialize ffmpeg helper
        $ffmpegHelper = new FfmpegHelper("Tools/ffmpeg/bin/ffmpeg.exe");
        $ffmpegOutputDirectory = str_replace("\\", "/", $this->outputDirectory);

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
            $audioListPath = $this->outputDirectory . "tmp/Audio/list.txt";

            for ($i = 0; $i < count($this->frames); $i++)
            {
                echo "\rGenerating audio ... " . ($i + 1) . "/" . $amountFrames;
                $outputPath = "tmp/Audio/" . $i . ".wav";

                // Generate random beep sound
                $ffmpegHelper->resetOptions();
                $ffmpegHelper->addOption("-f lavfi");
                $ffmpegHelper->addOption("-i \"sine=frequency=" . (10000 * $this->fillPercentages[$i]) . ":duration=1\"");
                $ffmpegHelper->addOption("-t " . $secondsPerFrame);

                exec($ffmpegHelper->generateCommand($ffmpegOutputDirectory . $outputPath));

                file_put_contents($audioListPath, "file '" . $i . ".wav'\r\n", FILE_APPEND);
            }
        }

        echo "\nGenerating video file ...";

        // Create video
        $ffmpegHelper->resetOptions();

        if ($this->hasSound == true)
        {
            // Create single sound from sound frames
            $ffmpegHelper->addOption("-f concat");
            $ffmpegHelper->addOption("-safe 0");
            $ffmpegHelper->addOption("-i \"" . $ffmpegOutputDirectory . "tmp/Audio/list.txt\"");
        }

        // Create video from image frames
        $ffmpegHelper->addOption("-framerate " . $this->fps);
        $ffmpegHelper->addOption("-i \"" . $ffmpegOutputDirectory . "tmp/Frames/%d.png\""); // Input Images
        $ffmpegHelper->addOption("-pix_fmt yuv420p");

        // Save video in output folder
        exec($ffmpegHelper->generateCommand("\"" . $this->outputDirectory . "/Video/" . $fileName . "\""));

        unset($this->imageCreator);
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory . "/tmp", true);

        echo "\nVideo creation complete!\n\n";
    }
}