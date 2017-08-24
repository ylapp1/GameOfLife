<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 */

namespace Output;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class GIFOutput
 *
 * @package Output
 */
class GIFOutput extends BaseOutput
{
    private $frameTime = 20;
    private $tmpPath = __DIR__ . "/../../../Output/tmp/Frames";
    private $outputPath = __DIR__ . "/../../../Output/Gif/";
    private $frames = array();
    private $imageCreator;

    /**
     * Adds GIFOutputs specific option to an option list
     *
     * @param Getopt $_options      The option list to which the options are added
     */
    function addOptions($_options)
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
     * Initializes the Gif output
     *
     * @param Getopt $_options
     */
    function startOutput($_options)
    {
        echo "Starting GIF Output...\n";

        // get board dimensions
        if ($_options->getOption("width")) $boardWidth = intval($_options->getOption("width"));
        else $boardWidth = 20;

        if ($_options->getOption("height")) $boardHeight = intval($_options->getOption("height"));
        else $boardHeight = 10;

        $colorSelector = new ColorSelector();

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

        if (! file_exists($this->tmpPath)) mkdir($this->tmpPath, 0777, true);
        if (! file_exists($this->outputPath)) mkdir($this->outputPath, 0777, true);
        $this->imageCreator = new ImageCreator($boardHeight, $boardWidth, $cellSize, $cellColor, $backgroundColor, $gridColor, "/tmp/Frames");
    }

    /**
     * Creates single Gif files to compile
     *
     * @param Board $_board
     */
    function outputBoard($_board)
    {
        $this->frames[] = $this->imageCreator->createImage($_board, "gif");

        echo "\rGamestep: " . ($_board->gameStep() + 1);
    }

    /**
     * Creates the animated Gif from single files
     * Uses GIFEncoder class
     *
     */
    function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead or a repeating pattern was detected.";
        echo "\nStarting GIF creation. One moment please...";

        $frameDurations = array();

        for ($i = 0; $i < count($this->frames) - 1; $i++)
        {
            $frameDurations[] = $this->frameTime;
        }

        $frameDurations[] = $this->frameTime + 200;

        $gif = new GIFEncoder($this->frames, $frameDurations, 0, 2, 1, 0, 0, "url");
        $fileNameCount = 0;
        do
        {
            $fileNameCount++;
        } while (file_exists($this->outputPath . "Gif_$fileNameCount.gif"));

        if (fwrite(fopen($this->outputPath . "Gif_$fileNameCount.gif", "wb"), $gif->GetAnimation()) == false)
        {
            echo "An error occurred during the gif creation. Stopping...";
            die();
        };

        // Delete all frames
        foreach ($this->frames as $frame)
        {
            unlink($frame);
        }
        // Delete frames directory
        rmdir($this->tmpPath);

        unset($this->imageCreator);

        echo "\nGIF creation complete.";
    }
}