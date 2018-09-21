<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use Simulator\Board;
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
     * @param Getopt $_options The option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Fills a board with cells.
     *
     * @codeCoverageIgnore
     *
     * @param Board $_board The board which will be filled with cells
     * @param Getopt $_options The option list
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
    }
}
