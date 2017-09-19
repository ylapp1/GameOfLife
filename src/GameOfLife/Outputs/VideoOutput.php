<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use Utils\FileSystemHandler;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use Output\Helpers\ImageCreator;
use Output\Helpers\ColorSelector;
use Output\Helpers\ImageColor;
use Output\Helpers\FfmpegHelper;

/**
 * Class VideoOutput
 *
 * Create a video with sound from the game of life boards
 *
 * @package Output
 */
class VideoOutput extends BaseOutput
{
    private $fps = 15;
    private $secondsPerFrame;
    private $frames = array();
    /** @var ImageCreator $imageCreator */
    private $imageCreator;
    private $fillPercentages = array();
    /** @var FileSystemHandler */
    private $fileSystemHandler;

    /**
     * Adds VideoOutputs specific option to an option list
     *
     * @param Getopt $_options      The option list to which the options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "videoOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for video outputs"),
                array(null, "videoOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for video outputs"),
                array(null, "videoOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Background color for video outputs"),
                array(null, "videoOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Grid color for video outputs"),
                array(null, "videoOutputFPS", Getopt::REQUIRED_ARGUMENT, "Frames per second of videos")));
    }

    /**
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        echo "Starting video output ...\n";

        $colorSelector = new ColorSelector();
        $this->fileSystemHandler = new FileSystemHandler();

        // fetch options
        if ($_options->getOption("videoOutputSize")) $cellSize = intval($_options->getOption("videoOutputSize"));
        else $cellSize = 100;

        $inputCellColor = $_options->getOption("videoOutputCellColor");
        if ($inputCellColor != false) $cellColor = $colorSelector->getColor($inputCellColor);
        else $cellColor = new ImageColor(0, 0, 0);

        $inputBackgroundColor = $_options->getOption("videoOutputBackgroundColor");
        if ($inputBackgroundColor != false) $backgroundColor = $colorSelector->getColor($inputBackgroundColor);
        else $backgroundColor = new ImageColor(255, 255,255);

        $inputGridColor = $_options->getOption("videoOutputGridColor");
        if ($inputGridColor != false) $gridColor = $colorSelector->getColor($inputGridColor);
        else $gridColor = new ImageColor(0,0,0);

        if ($_options->getOption("videoOutputFPS")) $this->fps = intval($_options->getOption("videoOutputFPS"));

        $this->fileSystemHandler->createDirectory($this->outputDirectory . "Video");
        $this->fileSystemHandler->createDirectory($this->outputDirectory . "tmp/Frames");
        $this->fileSystemHandler->createDirectory($this->outputDirectory . "tmp/Audio");

        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor, "tmp/Frames");

        $this->secondsPerFrame = floatval(ceil(1000/$this->fps) / 1000);
    }

    /**
     * Creates single Gif files to compile
     *
     * @param Board $_board
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->frames[] = $this->imageCreator->createImage($_board, "video");
        $this->fillPercentages[] = $_board->getFillpercentage();
    }

    /**
     * Creates the video file from the frames and adds a sound
     * Uses GIFEncoder class
     *
     */
    public function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead or a repeating pattern was detected.";
        echo "\nStarting video creation ...\n";

        $fileName = "Game_" . $this->getNewGameId("Video") . ".mp4";

        // Initialize ffmpeg helper
        $ffmpegHelper = new FfmpegHelper("Tools/ffmpeg/bin/ffmpeg.exe");

        // generate Audio files for each frame
        $audioFiles = array();

        if (count($this->frames) == 0)
        {
            echo "Error: No frames in frames folder found!\n";
            return;
        }

        $amountFrames = count($this->frames);

        for ($i = 0; $i < count($this->frames); $i++)
        {
            echo "\rGenerating audio ... " . ($i + 1) . "/" . $amountFrames;
            $outputPath = "tmp/Audio/" . $i . ".wav";

            // Generate random beep sound
            $ffmpegHelper->resetOptions();
            $ffmpegHelper->addOption("-f lavfi");
            $ffmpegHelper->addOption("-i \"sine=frequency=" . (10000 * $this->fillPercentages[$i]) . ":duration=1\"");
            $ffmpegHelper->addOption("-t " . $this->secondsPerFrame);

            exec($ffmpegHelper->generateCommand($this->outputDirectory . $outputPath));

            $audioFiles[] = $outputPath;

            file_put_contents($this->outputDirectory . "tmp/Audio/list.txt", "file Output/'" . $outputPath . "'\r\n", FILE_APPEND);
        }

        echo "\nGenerating video file ...";

        // Create video with sound
        $ffmpegHelper->resetOptions();

        // Create single sound from sound frames
        $ffmpegHelper->addOption("-f concat");
        $ffmpegHelper->addOption("-safe 0");
        $ffmpegHelper->addOption("-i \"Output\\tmp\\Audio\\list.txt\"");

        // Create video from image frames
        $ffmpegHelper->addOption("-framerate " . $this->fps);
        $ffmpegHelper->addOption("-i \"Output\\tmp\\Frames\\%d.png\""); // Input Images
        $ffmpegHelper->addOption("-pix_fmt yuv420p");

        // Save video in output folder
        exec($ffmpegHelper->generateCommand("\"Output\\Video\\" . $fileName . "\""));

        unset($this->imageCreator);
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory . "/tmp", true);

        echo "\nVideo creation complete!\n\n";
    }
}