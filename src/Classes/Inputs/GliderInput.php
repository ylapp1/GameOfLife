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
 */
class GliderInput extends BaseInput
{
    /**
     * Place a glider on the board
     *
     * @param Board $_board     The board
     * @param Getopt $_options  Options
     */
    public function fillBoard($_board, $_options)
    {
        $posX = $_options->getOption("posX");
        $posY = $_options->getOption("posY");

        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;


        if ($posX + 3 >= $_board->width() || $posY + 3 >= $_board->height())
        {
            echo "Error: Glider exceeds field borders.";
            return;
        }


        $_board->setField($posX + 1, $posY, true);
        $_board->setField($posX + 2, $posY + 1, true);
        $_board->setField($posX, $posY + 2, true);
        $_board->setField($posX + 1, $posY + 2, true);
        $_board->setField($posX + 2, $posY + 2, true);


        //echo "Board dimensions: " . $_board->width() . " x " . $_board->height() . "\n";
        //echo "Glider Position: " . $posX . " | " . $posY . "\n";
    }


    /**
     * Adds its own specific options to the option list
     *
     * @param Getopt $_options
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "posX", Getopt::REQUIRED_ARGUMENT, "X position of the object (blinker, glider etc.)"),
                array(null, "posY", Getopt::REQUIRED_ARGUMENT, "Y position of the object (blinker, glider, etc.)")
            )
        );
    }
}