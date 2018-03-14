<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * BaseOutput from which all other outputs must inherit.
 *
 * addOptions() adds options to a Getopt object
 * startOutput() initializes variables that are necessary for the output
 * outputBoard() outputs a single board
 * finishOutput() processes the output boards to create the final file
 */
class BaseOutput
{
    /**
     * Adds output specific options to the option list.
     *
     * @param Getopt $_options Current option list
     *
     * @codeCoverageIgnore
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Start output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     *
     * @codeCoverageIgnore
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
    }

    /**
     * Output one game step.
     *
     * @param Board $_board Current board
     *
     * @codeCoverageIgnore
     */
    public function outputBoard(Board $_board)
    {
    }

    /**
     * Finish output (Display that simulation is finished, write files and delete temporary files).
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     */
    protected function finishOutput(String $_simulationEndReason)
    {
        echo "\nSimulation finished: " . $_simulationEndReason . ".\n\n";
    }
}
