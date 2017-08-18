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
        $files = glob(__DIR__ . "/../../../Output/PNG/Game_*");

        if (count($files) == 0) $lastGameId = 0;
        else
        {
            $lastGameName = basename($files[count($files) - 1]);
            $lastGameData = explode("_", $lastGameName);
            $lastGameId = intval($lastGameData[1]);
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
        imagefill($image, 0, 0, $colorWhite);

        // color alive = black
        $colorAlive = imagecolorallocate($image, 0, 0, 0);

        // color dead = white
        $colorDead = $colorWhite;

        for ($y = 0; $y < $_board->height(); $y++)
        {
            for ($x = 0; $x < $_board->width(); $x++)
            {
                if ($_board->getField($x, $y) == true)
                {
                    imagefilledrectangle($image,
                                        $x * $this->cellSize,
                                        $y * $this->cellSize,
                                        $x * $this->cellSize + $this->cellSize,
                                        $y * $this->cellSize + $this->cellSize,
                                        $colorAlive);
                }
                else
                {
                    imagefilledrectangle($image,
                        $x * $this->cellSize,
                        $y * $this->cellSize,
                        $x * $this->cellSize + $this->cellSize,
                        $y * $this->cellSize + $this->cellSize,
                        $colorDead);
                }
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