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
use Utils\Shell\ShellInformationFetcher;
use Utils\Shell\ShellOutputHelper;

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
     * The title of the output that will be printed when the output is started
     *
     * @var String $outputTitle
     */
    protected $outputTitle;

    /**
     * The shell information fetcher
     *
     * @var ShellInformationFetcher $shellInformationFetcher
     */
    protected $shellInformationFetcher;

    /**
     * The shell output helper
     *
     * @var ShellOutputHelper $shellOutputHelper
     */
    protected $shellOutputHelper;


    /**
     * BaseOutput constructor.
     *
     * @param String $_outputTitle The title of the output that will be printed when the output is started
     */
    protected function __construct(String $_outputTitle)
    {
        $this->outputTitle = $_outputTitle;
        $this->shellInformationFetcher = new ShellInformationFetcher();
        $this->shellOutputHelper = new ShellOutputHelper();
    }


    /**
     * Prints the title of the output to the screen.
     */
    protected function printTitle()
    {
        $mainTitle = "GAME OF LIFE";

        echo $this->shellOutputHelper->getCenteredOutputString($mainTitle);
        echo "\n" . $this->shellOutputHelper->getCenteredOutputString($this->outputTitle) . "\n\n";
    }

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
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        echo str_repeat("\n", $this->shellInformationFetcher->getNumberOfShellLines());
        $this->shellOutputHelper->clearScreen();
        $this->printTitle();
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
    public function finishOutput(String $_simulationEndReason)
    {
        echo "\nSimulation finished: " . $_simulationEndReason . ".\n\n";
    }
}
