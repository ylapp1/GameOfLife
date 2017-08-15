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
    private const spaceShipWidth = 5;
    private const spaceShipHeight = 4;


    /**
     * Place one spaceship on the board
     *
     * @param Board $_board     The Board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard($_board, $_options)
    {
        $posX = $_options->getOption("posX");

        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        else $posX -= 1;


        $posY = $_options->getOption("posY");

        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;
        else $posY -= 1;


        if ($posX < 0 ||
            $posY < 0 ||
            $posX + self::spaceShipWidth > $_board->width() ||
            $posY + self::spaceShipHeight > $_board->height()
           )
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
     * @param Getopt $_options  Options to which the object shall add its specific options
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