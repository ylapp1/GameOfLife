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
 * BaseInput from which all other inputs must inherit
 *
 * addOptions() Adds options to a Getopt object
 * fillBoard() Fills the board with cells
 */
class BaseInput
{
    /**
     * Adds object specific options
     *
     * @param Getopt $_options     Option list to which the objects options are added
     *
     * @codeCoverageIgnore
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Fills a given board with cells
     *
     * @param Board $_board       The board which shall be filled with cells
     * @param Getopt $_options    Object specific options (e.g. posX, posY, fillPercent)
     *
     * @codeCoverageIgnore
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
    }
}