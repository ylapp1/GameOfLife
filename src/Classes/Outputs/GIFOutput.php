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
    private $framePath = __DIR__ . "/../../../Output/GIF/Frames/";
    private $frames = array();
    private $removeFramesAfterCreation = true;
    private $cellSize = 100;
    private $cellAliveColor;
    private $backgroundColor;

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
                array(null, "gifOutputFrameTime", Getopt::REQUIRED_ARGUMENT, "Time for which each frame of a gif is displayed (in milliseconds * 10)")));
    }

    /**
     * Initializes the Gif output
     *
     * @param Getopt $_options
     */
    function startOutput($_options)
    {
        echo "Starting GIF Output...";

        $colorSelector = new ColorSelector();

        // fetch options
        if ($_options->getOption("gifOutputSize")) $this->cellSize = intval($_options->getOption("gifOutputSize"));

        $cellColor = $_options->getOption("gifOutputCellColor");
        if ($cellColor != false) $this->cellAliveColor = $colorSelector->getColor($cellColor);
        else $this->cellAliveColor = new ImageColor(0, 0, 0);

        $backgroundColor = $_options->getOption("gifOutputBackgroundColor");
        if ($backgroundColor != false) $this->backgroundColor = $colorSelector->getColor($backgroundColor);
        else $this->backgroundColor = new ImageColor(255, 255,255);

        if ($_options->getOption("gifOutputFrameTime")) $this->frameTime = intval($_options->getOption("gifOutputFrameTime"));


        if (! file_exists($this->framePath)) mkdir($this->framePath, 0777, true);
    }

    /**
     * Creates single Gif files to compile
     *
     * @param Board $_board
     */
    function outputBoard($_board)
    {
        $imageCreator = new ImageCreator($_board, $this->cellSize, $this->cellAliveColor, $this->backgroundColor);

        $this->frames[] = $imageCreator->createImage($_board, "gif");

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
        } while (file_exists($this->framePath . "../Gif_$fileNameCount.gif"));

        if (fwrite(fopen($this->framePath . "../Gif_$fileNameCount.gif", "wb"), $gif->GetAnimation()) == false)
        {
            echo "An error occurred during the gif creation. Stopping...";
            die();
        };

        if ($this->removeFramesAfterCreation == true)
        {
            // Delete all frames
            foreach ($this->frames as $frame)
            {
                unlink($frame);
            }
            // Delete frames directory
            rmdir($this->framePath);
        }

        echo "\nGIF creation complete.";
    }
}