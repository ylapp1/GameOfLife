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
 * Class SpaceShipInput
 *
 * Places a spaceship on the board
 *
 * Usage:
 *   - Call addOptions($_options) to add the objects options to the main option list
 *   - Call fillBoard to set the spaceship on the board
 */
class SpaceShipInput extends BaseInput
{
    // Magic Methods

    /**
     * SpaceShipInput constructor.
     */
    public function __construct()
    {
        parent::__construct(5, 4);
    }


    /**
     * Adds SpaceShipInputs specific options to the option list
     *
     * @param Getopt $_options  Options to which the object shall add its specific options
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array
            (
                array(null, "spaceShipPosX", Getopt::REQUIRED_ARGUMENT, "X position of the spaceship"),
                array(null, "spaceShipPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the spaceship")
            )
        );
    }

    /**
     * Places the spaceship on the board
     *
     * @param Board $_board     The Board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        $boardCenter = $_board->getCenter();
        $posX = $boardCenter["x"];
        $posY = $boardCenter["y"];

        if ($_options->getOption("spaceShipPosX")) $posX = (int)$_options->getOption("spaceShipPosX");
        if ($_options->getOption("spaceShipPosY")) $posY = (int)$_options->getOption("spaceShipPosY");

        // check whether the spaceship is inside board dimensions
        if ($this->isObjectOutOfBounds($_board->width(), $_board->height(), $posX, $posY))
        {
            echo "Error: Spaceship exceeds field borders.\n";
        }
        else
        {
            // Set the cells
            $_board->setField($posX + 1, $posY, true);
            $_board->setField($posX + 2, $posY, true);
            $_board->setField($posX + 3, $posY, true);
            $_board->setField($posX + 4, $posY, true);
            $_board->setField($posX, $posY + 1, true);
            $_board->setField($posX + 4, $posY + 1, true);
            $_board->setField($posX + 4, $posY + 2, true);
            $_board->setField($posX, $posY + 3, true);
            $_board->setField($posX + 3, $posY + 3, true);
        }
    }
}