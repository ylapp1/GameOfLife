<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * BaseInput from which all other inputs must inherit.
 *
 * Call addOptions($_options) to add input specific options to a Getopt object
 * Call fillBoard($_board) to fill a board with cells
 */
class BaseInput
{
    /**
     * Adds object specific options.
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $_options Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Fills a given board with cells.
     *
     * @codeCoverageIgnore
     *
     * @param Board $_board The board which shall be filled with cells
     * @param Getopt $_options Object specific options (e.g. posX, posY, fillPercent)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
    }
}
