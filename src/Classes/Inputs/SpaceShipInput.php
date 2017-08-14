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
 */
class SpaceShipInput extends BaseInput
{
    /**
     * @param Board $_board
     * @param Getopt $_options
     */
    public function fillBoard($_board, $_options)
    {
        $posX = $_options->getOption("posX");
        $posY = $_options->getOption("posY");

        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;


        if ($posX + 4 >= $_board->width() || $posY + 3 >= $_board->height())
        {
            echo "Error: Spaceship exceeds field borders.";
            return;
        }


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


    /**
     * Adds its own specific options to the option list
     *
     * @param Getopt $_options
     */
    public function addOptions($_options)
    {
        /*
        $_options->addOptions(
            array(
                array(null, "posX", Getopt::REQUIRED_ARGUMENT, "X position of the spaceship"),
                array(null, "posY", Getopt::REQUIRED_ARGUMENT, "Y position of the spaceship")
            )
        );*/
    }
}