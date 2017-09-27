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
 * Places a 1x3 blinker on the board
 *
 * Usage:
 *   - Call addOptions($_options) to add the objects options to the main option list
 *   - Call fillBoard() to set the blinker on the board
 */
class BlinkerInput extends ObjectInput
{
    // Magic Methods

    /**
     * BlinkerInput constructor.
     */
    public function __construct()
    {
        parent::__construct(1, 3, "blinker");
    }

    /**
     * Places the blinker on the board
     *
     * @param Board $_board     The Board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard (Board $_board, Getopt $_options)
    {
        $boardCenter = $_board->getCenter();
        $posX = $boardCenter["x"];
        $posY = $boardCenter["y"];

        if ($_options->getOption("blinkerPosX")) $posX = (int)$_options->getOption("blinkerPosX");
        if ($_options->getOption("blinkerPosY")) $posY = (int)$_options->getOption("blinkerPosY");

        // check whether the blinker is inside the board dimensions
        if ($this->isObjectOutOfBounds($_board->width(), $_board->height(), $posX, $posY))
        {
            echo "Error: Blinker exceeds field borders.\n";
        }
        else
        {
            // Set the cells
            $_board->setField($posX, $posY, true);
            $_board->setField($posX,$posY + 1,true);
            $_board->setField($posX,$posY + 2,true);
        }
    }
}