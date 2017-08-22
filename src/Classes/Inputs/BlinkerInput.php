<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * Class BlinkerInput
 *
 * Places a 3x1 blinker on the board
 *
 * Usage:
 * Use addOptions($_options) to add the objects options to the main option list
 * Use fillBoard to set the blinker on the board
 */
class BlinkerInput extends BaseInput
{
    private $blinkerWidth = 1;
    private $blinkerHeight = 3;

    /**
     * Adds BlinkeInputs specific options to the option list
     *
     * @param Getopt $_options  Option list to which the objects options are added
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "blinkerPosX", Getopt::REQUIRED_ARGUMENT, "X position of the blinker"),
                array(null, "blinkerPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the blinker"))
        );
    }

    /**
     * Places the blinker on the board
     *
     * @param Board $_board     The Board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard ($_board, $_options)
    {
        // fetch options
        $posX = $_options->getOption("blinkerPosX");
        $posY = $_options->getOption("blinkerPosY");

        // use default values (center) if options not set
        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        else $posX -= 1;

        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;
        else $posY -= 1;

        // check whether the blinker is inside board dimensions
        if ($posX < 0 ||
            $posY < 0 ||
            $posX + $this->blinkerWidth > $_board->width() ||
            $posY + $this->blinkerHeight > $_board->height())
        {
            echo "Error: Blinker exceeds field borders.";
            return;
        }

        // Set the cells
        $_board->setField($posX,$posY, true);
        $_board->setField($posX,$posY + 1,true);
        $_board->setField($posX,$posY + 2,true);
    }
}