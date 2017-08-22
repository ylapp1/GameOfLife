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
    private $cellSize = 100;
    private $frameTime = 20;
    private $framePath = __DIR__ . "/../../../Output/GIF/Frames/";
    private $frames = array();
    private $removeFramesAfterCreation = true;

    /**
     * Initializes the Gif output
     *
     * @param Getopt $_options
     */
    function startOutput($_options)
    {
        if (!file_exists($this->framePath)) mkdir($this->framePath, 0777, true);
        echo "Starting GIF Output...";
    }

    /**
     * Creates single Gif files to compile
     *
     * @param Board $_board
     */
    function outputBoard($_board)
    {
        $image = imagecreate($_board->width() * $this->cellSize, $_board->height() * $this->cellSize);

        // Set background of image to white
        $colorWhite = imagecolorallocate($image, 255, 255, 255);
        $colorBlack = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $colorWhite);

        $colorAlive = $colorBlack;

        // Draw the cells
        for ($y = 0; $y < imagesy($image); $y += $this->cellSize)
        {
            for ($x = 0; $x < imagesx($image); $x += $this->cellSize)
            {
                if ($_board->getField($x / $this->cellSize, $y / $this->cellSize) == true)
                {
                    imagefilledellipse($image, $x + $this->cellSize / 2, $y + $this->cellSize / 2, $this->cellSize - 5, $this->cellSize - 5, $colorAlive);
                }
            }
        }

        // Draw a grid
        for ($y = 0; $y < imagesy($image); $y += $this->cellSize)
        {
            for ($x = 0; $x < imagesx($image); $x += $this->cellSize)
            {
                // vertical
                imageline($image, $x, 0, $x, imagesy($image), $colorBlack);

                // horizontal
                imageline($image, 0, $y, imagesx($image), $y, $colorBlack);
            }
        }


        $framePath = $this->framePath . "/" . ($_board->gameStep() + 1) . ".gif";

        imagegif($image, $framePath);
        imagedestroy($image);

        $this->frames[] = $framePath;

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

        $frameDuration = array();

        for ($i = 0; $i < count($this->frames) - 1; $i++)
        {
            $frameDuration[] = $this->frameTime;
        }

        $frameDuration[] = $this->frameTime + 200;

        $gif = new GIFEncoder($this->frames, $frameDuration, 0, 2, 1, 0, 0, "url");
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
            for ($f = 0; $f < count($this->frames); $f++)
            {
                unlink($this->frames[$f]);
            }
            rmdir($this->framePath);
        }

        echo "\nGIF creation complete.";
    }

    function addOptions($_options)
    {
    }
}
