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
class VideoOutput extends BaseOutput
{
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    private $fillPercentages = array();
    private $fps = 15;
    private $frames = array();
    /** @var ImageCreator $imageCreator */
    private $imageCreator;
    private $secondsPerFrame;


    /**
     * Returns the filesystem handler of this video output
     *
     * @return FileSystemHandler    Filesystem handler of this video output
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the filesystem handler of this video output
     *
     * @param FileSystemHandler $_fileSystemHandler      Filesystem handler of this video output
     */
    public function setFileSystemHandler(FileSystemHandler $_fileSystemHandler)
    {
        $this->fileSystemHandler = $_fileSystemHandler;
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
     * Returns the image creator of this video output
     *
     * @return ImageCreator     Image creator of this video output
     */
    public function imageCreator(): ImageCreator
    {
        return $this->imageCreator;
    }

    /**
     * Sets the image creator of this video output
     *
     * @param ImageCreator $_imageCreator   Image creator of this video output
     */
    public function setImageCreator(ImageCreator $_imageCreator)
    {
        $this->imageCreator = $_imageCreator;
    }

    /**
     * Returns the seconds per frame of this video output
     *
     * @return float      Seconds per frame of this video output
     */
    public function secondsPerFrame(): float
    {
        return $this->secondsPerFrame;
    }

    /**
     * Sets the seconds per frame of this video output
     *
     * @param float $_secondsPerFrame     Seconds per frame of this video output
     */
    public function setSecondsPerFrame(float $_secondsPerFrame)
    {
        $this->secondsPerFrame = $_secondsPerFrame;
    }

    /**
     * Adds VideoOutputs specific options to an option list
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

        $imageOutputPath = $this->outputDirectory . "tmp/Frames";
        $this->fileSystemHandler->createDirectory($this->outputDirectory . "Video");
        $this->fileSystemHandler->createDirectory($imageOutputPath);
        $this->fileSystemHandler->createDirectory($this->outputDirectory . "tmp/Audio");

        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor, $imageOutputPath);

        $this->secondsPerFrame = floatval(ceil(1000/$this->fps) / 1000);
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