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
    private $gliderWidth = 3;
    private $gliderHeight = 3;

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

        // Use default values (center) if options are not set
        if ($posX == null) $posX = ceil($_board->width() / 2) - 1;
        else $posX -= 1;

        if ($posY == null) $posY = ceil($_board->height() / 2) - 1;
        else $posY -= 1;

        // check whether the glider is inside board dimensions
        if ($posX < 0 ||
            $posY < 0 ||
            $posX + $this->gliderWidth > $_board->width() ||
            $posY + $this->gliderHeight > $_board->height())
        {
            echo "Error: Glider exceeds field borders.";
            return;
        }

        // Set the cells
        $_board->setField($posX + 1, $posY, true);
        $_board->setField($posX + 2, $posY + 1, true);
        $_board->setField($posX,$posY + 2, true);
        $_board->setField($posX + 1, $posY + 2, true);
        $_board->setField($posX + 2, $posY + 2, true);
    }
}