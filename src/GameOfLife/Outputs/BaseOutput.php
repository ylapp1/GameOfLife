<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use BoardRenderer\BaseBoardRenderer;
use Simulator\Board;
use Ulrichsg\Getopt;
use Util\Shell\ShellOutputHelper;

/**
 * BaseOutput from which all other outputs must inherit.
 *
 * addOptions() adds options to a Getopt object
 * startOutput() initializes variables that are necessary for the output
 * outputBoard() outputs a single board
 * finishOutput() finishes the output (processes created images, deletes temporary files, etc.)
 */
abstract class BaseOutput
{
    // Attributes

    /**
     * The title of the output that will be printed when the output is started
     *
     * @var String $outputTitle
     */
    protected $outputTitle;

    /**
     * The shell output helper
     *
     * @var ShellOutputHelper $shellOutputHelper
     */
    protected $shellOutputHelper;

	/**
	 * The board renderer
	 *
	 * @var BaseBoardRenderer $boardRenderer
	 */
    protected $boardRenderer;


    // Magic Methods

    /**
     * BaseOutput constructor.
     *
     * @param String $_outputTitle The title of the output that will be printed when the output is started
     */
    protected function __construct(String $_outputTitle)
    {
        $this->outputTitle = $_outputTitle;
        $this->shellOutputHelper = new ShellOutputHelper();
    }


    // Class Methods

    /**
     * Prints the title of the output to the screen.
     */
    protected function printTitle()
    {
        $mainTitle = "GAME OF LIFE";
        $titleString = "\n" . $mainTitle . "\n" . $this->outputTitle . "\n\n";

        $this->shellOutputHelper->printCenteredOutputString($titleString);
    }

    /**
     * Adds output specific options to the option list.
     *
     * @param Getopt $_options The option list
     */
    abstract public function addOptions(Getopt $_options);

    /**
     * Initializes the output.
     *
     * @param Getopt $_options The option list
     * @param Board $_board The initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        $this->shellOutputHelper->clearScreen();
        $this->printTitle();
    }

    /**
     * Outputs one board.
     *
     * @param Board $_board The current board
     * @param int $_gameStep The current game step
     */
    abstract public function outputBoard(Board $_board, int $_gameStep);

    /**
     * Finishes the output.
     * This method displays that the simulation is finished, writes files and deletes temporary files (if necessary).
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     */
    public function finishOutput(String $_simulationEndReason)
    {
        echo "\nSimulation finished: " . $_simulationEndReason . ".\n\n";
    }
}
