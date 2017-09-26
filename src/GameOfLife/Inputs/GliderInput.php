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
 * Places a glider on the board
 *
 * Usage:
 *   - Call addOptions($_options) to add the objects options to the main option list
 *   - Call fillBoard to set the glider on the board
 */
class GliderInput extends BaseInput
{
    // Magic Methods

    /**
     * GliderInput constructor.
     */
    public function __construct()
    {
        parent::__construct(3, 3);
    }


    /**
     * Adds GliderInputs specific options to the option list
     *
     * @param Getopt $_options  Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array
            (
                array(null, "gliderPosX", Getopt::REQUIRED_ARGUMENT, "X position of the glider"),
                array(null, "gliderPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the glider")
            )
        );
    }

    /**
     * Places the glider on the board
     *
     * @param Board $_board     The board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        $boardCenter = $_board->getCenter();
        $posX = $boardCenter["x"];
        $posY = $boardCenter["y"];

        if ($_options->getOption("gliderPosX")) $posX = (int)$_options->getOption("gliderPosX");
        if ($_options->getOption("gliderPosY")) $posY = (int)$_options->getOption("gliderPosY");

        // check whether the glider is inside board dimensions
        if ($this->isObjectOutOfBounds($_board->width(), $_board->height(), $posX, $posY))
        {
            echo "Error: Glider exceeds field borders.\n";
        }
        else
        {
            // Set the cells
            $_board->setField($posX + 1, $posY, true);
            $_board->setField($posX + 2, $posY + 1, true);
            $_board->setField($posX,$posY + 2, true);
            $_board->setField($posX + 1, $posY + 2, true);
            $_board->setField($posX + 2, $posY + 2, true);
        }
    }
}