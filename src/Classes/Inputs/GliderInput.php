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
    private $gliderWidth = 3;
    private $gliderHeight = 3;


    /**
     * Place a glider on the board
     *
     * @param Board $_board     The board
     * @param Getopt $_options  Options (posX, posY)
     */
    public function fillBoard($_board, $_options)
    {
        $posX = $_options->getOption("gliderPosX");

        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        else $posX -= 1;


        $posY = $_options->getOption("gliderPosY");

        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;
        else $posY -= 1;


        if ($posX < 0 ||
            $posY < 0 ||
            $posX + $this->gliderWidth > $_board->width() ||
            $posY + $this->gliderHeight > $_board->height()
           )
        {
            echo "Error: Glider exceeds field borders.";
            return;
        }


        $_board->setField($posX + 1, $posY, true);
        $_board->setField($posX + 2, $posY + 1, true);
        $_board->setField($posX,$posY + 2, true);
        $_board->setField($posX + 1, $posY + 2, true);
        $_board->setField($posX + 2, $posY + 2, true);
    }


    /**
     * Adds its own specific options to the option list
     *
     * @param Getopt $_options  Options to which the object shall add its specific options
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
}