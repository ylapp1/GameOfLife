<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class BaseInput
 *
 * Fills the board with cells
 * Used as base for child classes which fill the board with specific sets of cells
 */
class BaseInput
{
    /**
     * Adds object specific options
     *
     * @param Getopt $_options     Option list to which the objects options are added
     */
    public function addOptions($_options)
    {
    }

    /**
     * Fills a given board with cells
     *
     * @param Board $_board      The board which shall be filled with cells
     * @param Getopt $_options    Object specific options (e.g. posX, posY, fillPercent)
     */
    public function fillBoard($_board, $_options)
    {
    }
}