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
 * @codeCoverageIgnore
 */
class BaseOutput
{
    protected $outputDirectory = __DIR__ . "/../../../Output/";

    /**
     * add output specific options to the option list
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
    }

    /**
     * Output one game step
     *
     * @param Board $_board     Current board
     */
    public function outputBoard(Board $_board)
    {
    }

    /**
     * Finish output (Display that simulation is finished, write files and delete temporary files)
     */
    public function finishOutput()
    {
    }
}