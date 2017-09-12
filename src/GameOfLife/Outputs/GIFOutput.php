<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 */

namespace Output;

use GameOfLife\FileSystemHandler;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use Output\Helpers\ImageCreator;
use Output\Helpers\ColorSelector;
use Output\Helpers\ImageColor;
use Output\Helpers\GIFEncoder;

/**
 * Class GIFOutput
 *
 * @package Output
 */
class GIFOutput extends BaseOutput
{
    private $frameTime = 20;
    private $frames = array();
    /** @var ImageCreator $imageCreator */
    private $imageCreator;
    /** @var FileSystemHandler */
    private $fileSystemHandler;

    /**
     * Adds GIFOutputs specific option to an option list
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
     * Start output
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


        $this->fileSystemHandler->createDirectory($this->outputDirectory . "tmp/Frames/");
        $this->fileSystemHandler->createDirectory($this->outputDirectory . "Gif");
        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor, "tmp/Frames");
    }

    /**
     * Creates single Gif files to compile
     *
     * @param Board $_board
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->frames[] = $this->imageCreator->createImage($_board, "gif");
    }

    /**
     * Creates the animated Gif from single files
     * Uses GIFEncoder class
     *
     */
    public function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead or a repeating pattern was detected.";
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