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
 */
class BlinkerInput extends BaseInput
{
    private const blinkerWidth = 1;
    private const blinkerHeight = 3;


    /**
     * Place one 3x1 Blinker on the board
     *
     * @param Board $_board     The Board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard ($_board, $_options)
    {
        $posX = $_options->getOption("blinkerPosX");

        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        else $posX -= 1;


        $posY = $_options->getOption("blinkerPosY");

        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;
        else $posY -= 1;


        if ($posX < 0 ||
            $posY < 0 ||
            $posX + self::blinkerWidth > $_board->width() ||
            $posY + self::blinkerHeight > $_board->height()
           )
        {
            echo "Error: Blinker exceeds field borders.";
            return;
        }



        $_board->setField($posX,$posY, true);
        $_board->setField($posX,$posY + 1,true);
        $_board->setField($posX,$posY + 2,true);
    }


    /**
     * Adds BlinkeInputs specific options to the option list
     *
     * @param Getopt $_options  Options to which the object shall add its specific options
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "blinkerPosX", Getopt::REQUIRED_ARGUMENT, "X position of the blinker"),
                array(null, "blinkerPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the blinker")
            )
        );
    }
}