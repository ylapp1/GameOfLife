<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class BaseOutput
 *
 * @package Output
 */
class BaseOutput
{
    /**
     * add output specific options to the option list
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions($_options)
    {

    }

    /**
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     */
    public function startOutput($_options)
    {

    }

    /**
     * Output one game step
     *
     * @param Board $_board     Current board
     */
    public function outputBoard($_board)
    {

    }

    /**
     * Finish output (write to file)
     */
    public function finishOutput()
    {

    }
}