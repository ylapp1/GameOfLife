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
 * Use addOptions($_options) to add the objects options to the main option list
 * Use fillBoard to set the spaceship on the board
 */
class SpaceShipInput extends BaseInput
{
    private $spaceShipWidth = 5;
    private $spaceShipHeight = 4;

    /**
     * Adds SpaceShipInputs specific options to the option list
     *
     * @param Getopt $_options  Options to which the object shall add its specific options
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "spaceShipPosX", Getopt::REQUIRED_ARGUMENT, "X position of the spaceship"),
                array(null, "spaceShipPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the spaceship"))
        );
    }

    /**
     * Places the spaceship on the board
     *
     * @param Board $_board     The Board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard($_board, $_options)
    {
        // fetch options
        $posX = $_options->getOption("spaceShipPosX");
        $posY = $_options->getOption("spaceShipPosY");

        // use default values (center) if options not set
        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        else $posX -= 1;

        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;
        else $posY -= 1;

        // check whether the spaceship is inside board dimensions
        if ($posX < 0 ||
            $posY < 0 ||
            $posX + $this->spaceShipWidth > $_board->width() ||
            $posY + $this->spaceShipHeight > $_board->height())
        {
            echo "Error: Spaceship exceeds field borders.";
            return;
        }

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