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
 * Class GliderInput
 *
 * Places a glider on the board
 *
 * Usage:
 * Use addOptions($_options) to add the objects options to the main option list
 * Use fillBoard to set the glider on the board
 */
class GliderInput extends BaseInput
{
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
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
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
    public function fillBoard($_board, $_options)
    {
        // fetch Options
        $posX = $_options->getOption("gliderPosX");
        $posY = $_options->getOption("gliderPosY");
        $boardCenter = $_board->getCenter();

        // Use default values if options are not set
        if ($posX == null) $posX = $boardCenter["x"];
        else $posX -= 1;

        if ($posY == null) $posY = $boardCenter["y"];
        else $posY -= 1;

        // check whether the glider is inside board dimensions
        if ($this->isObjectOutOfBounds($_board->width(), $_board->height(), $posX, $posY))
        {
            echo "Error: Glider exceeds field borders.";
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