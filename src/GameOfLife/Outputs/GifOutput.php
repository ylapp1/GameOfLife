<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use GIFEncoder\GIFEncoder;
use Output\Helpers\ColorSelector;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Saves the boards in an animated gif file
 *
 * @package Output
 */
class GifOutput extends BaseOutput
{
    private $frames = array();
    private $frameTime = 20;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var ImageCreator $imageCreator */
    private $imageCreator;


    /**
     * Returns the frame save paths
     *
     * @return array    Frame save paths
     */
    public function frames(): array
    {
        return $this->frames;
    }

    /**
     * Sets the frame save paths
     *
     * @param array $_frames    Frame save paths
     */
    public function setFrames(array $_frames)
    {
        $this->frames = $_frames;
    }

    /**
     * Returns the time per frame
     *
     * @return int      Time per frame
     */
    public function frameTime(): int
    {
        return $this->frameTime;
    }

    /**
     * Sets the time per frame
     *
     * @param int $_frameTime   Time per frame
     */
    public function setFrameTime(int $_frameTime)
    {
        $this->frameTime = $_frameTime;
    }

    /**
     * Returns the filesystem handler of this gif output
     *
     * @return FileSystemHandler    Filesystem handler
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the filesystem handler of this gif output
     *
     * @param FileSystemHandler $_fileSystemHandler     Filesystem handler
     */
    public function setFileSystemHandler(FileSystemHandler $_fileSystemHandler)
    {
        $this->fileSystemHandler = $_fileSystemHandler;
    }

    /**
     * Returns the image creator of this gif output
     *
     * @return ImageCreator     The image creator
     */
    public function imageCreator(): ImageCreator
    {
        return $this->imageCreator;
    }

    /**
     * Sets the image creator of this gif output
     *
     * @param ImageCreator $_imageCreator   The image creator
     */
    public function setImageCreator(ImageCreator $_imageCreator)
    {
        $this->imageCreator = $_imageCreator;
    }

    /**
     * Adds GIFOutputs specific options to a Getopt object
     *
     * @param Getopt $_options      The option list to which the options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "gifOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for gif outputs"),
                array(null, "gifOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for gif outputs"),
                array(null, "gifOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Background color for gif outputs"),
                array(null, "gifOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Grid color for gif outputs"),
                array(null, "gifOutputFrameTime", Getopt::REQUIRED_ARGUMENT, "Frame time of gif (in milliseconds * 10)")));
    }

    /**
     * Initializes the output
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        echo "Starting GIF Output...\n";

        $colorSelector = new ColorSelector();
        $this->fileSystemHandler = new FileSystemHandler();

        // fetch options
        if ($_options->getOption("gifOutputSize")) $cellSize = intval($_options->getOption("gifOutputSize"));
        else $cellSize = 100;

        $inputCellColor = $_options->getOption("gifOutputCellColor");
        if ($inputCellColor != false) $cellColor = $colorSelector->getColor($inputCellColor);
        else $cellColor = new ImageColor(0, 0, 0);

        $inputBackgroundColor = $_options->getOption("gifOutputBackgroundColor");
        if ($inputBackgroundColor != false) $backgroundColor = $colorSelector->getColor($inputBackgroundColor);
        else $backgroundColor = new ImageColor(255, 255,255);

        $inputGridColor = $_options->getOption("gifOutputGridColor");
        if ($inputGridColor != false) $gridColor = $colorSelector->getColor($inputGridColor);
        else $gridColor = new ImageColor(0,0,0);

        if ($_options->getOption("gifOutputFrameTime")) $this->frameTime = intval($_options->getOption("gifOutputFrameTime"));

        $imageOutputPath = $this->outputDirectory . "tmp/Frames";
        $this->fileSystemHandler->createDirectory($imageOutputPath);
        $this->fileSystemHandler->createDirectory($this->outputDirectory . "Gif");
        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor, $imageOutputPath);
    }

    /**
     * Creates a single Gif file for the current game step
     *
     * @param Board $_board     The board which will be output
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->frames[] = $this->imageCreator->createImage($_board, "gif");
    }

    /**
     * Creates an animated Gif from the gif files that were created by outputBoard()
     */
    public function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n";
        echo "\nStarting GIF creation. One moment please...";

        if (count($this->frames) == 0)
        {
            echo "Error: No frames in frames folder found!\n";
            return;
        }

        $frameDurations = array();

        for ($i = 0; $i < count($this->frames) - 1; $i++)
        {
            $frameDurations[] = $this->frameTime;
        }

        $frameDurations[] = $this->frameTime + 200;

        $gif = new GIFEncoder($this->frames, $frameDurations, 0, 2, 1, 0, 0, "url");
        $fileName = "Game_" . $this->getNewGameId("Gif") . ".gif";

        if (fwrite(fopen($this->outputDirectory . "Gif/" . $fileName, "wb"), $gif->GetAnimation()) == false)
        {
            echo "An error occurred during the gif creation. Stopping...";
            return;
        }

        unset($this->imageCreator);
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory . "/tmp", true);

        echo "\nGIF creation complete.";
    }
}