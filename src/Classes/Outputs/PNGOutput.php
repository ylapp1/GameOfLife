<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use Ulrichsg\Getopt;
Use GameOfLife\Board;

/**
 * Class PNGOutput
 *
 * @package Output
 */
class PNGOutput
{
    private $cellSize = 100;
    private $gameFolder;


    /**
     * add output specific options to the option list
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions($_options)
    {

    }

    /**
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     */
    public function startOutput($_options)
    {
        $fileNames = glob(__DIR__ . "/../../../Output/PNG/Game_*");

        if (count($fileNames) == 0) $lastGameId = 0;
        else
        {
            $fileIds = array();
            foreach ($fileNames as $fileName)
            {
                $fileData = explode("_", basename($fileName));
                $fileIds[] = intval($fileData[1]);
            }

            sort($fileIds, SORT_NUMERIC);
            $lastGameId = $fileIds[count($fileIds) - 1];
        }

        // Create new folder for current game
        $this->gameFolder = __DIR__ . "/../../../Output/PNG/Game_" . ($lastGameId + 1);
        mkdir($this->gameFolder);

        echo "Starting simulation ...\n\n";
    }

    /**
     * Output one game step
     *
     * @param Board $_board     Current board
     */
    public function outputBoard($_board)
    {
        $image = imagecreate($_board->width() * $this->cellSize, $_board->height() * $this->cellSize);

        // Set background of image to white
        $colorWhite = imagecolorallocate($image, 255, 255,255);
        $colorBlack = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $colorWhite);

        $colorAlive = $colorBlack;
        $colorDead = $colorWhite;

        // Draw the cells
        for ($y = 0; $y < imagesy($image); $y += $this->cellSize)
        {
            for ($x = 0; $x < imagesx($image); $x += $this->cellSize)
            {
                if ($_board->getField($x / $this->cellSize, $y / $this->cellSize) == true)
                {
                    //imagefilledrectangle($image, $x, $y,$x + $this->cellSize,$y + $this->cellSize, $colorAlive);

                    //imagefilledarc ($image, $x + $this->cellSize/2, $y + $this->cellSize/2, $this->cellSize, $this->cellSize, 0 , 360, $colorAlive, IMG_ARC_EDGED);
                    imagefilledellipse($image, $x + $this->cellSize/2, $y + $this->cellSize/2, $this->cellSize - 5, $this->cellSize - 5, $colorAlive);
                }
                /*else
                {
                    imagefilledrectangle($image, $x, $y,$x + $this->cellSize,$y + $this->cellSize, $colorDead);
                }*/
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

        imagepng($image, $this->gameFolder . "/" . ($_board->gameStep() + 1) . ".png");
        imagedestroy($image);

        echo "\rGamestep: " . ($_board->gameStep() + 1);
    }

    /**
     * Finish output (write to file)
     */
    public function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead or a repeating pattern was detected.";
    }
}