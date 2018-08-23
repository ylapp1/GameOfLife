<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use BoardRenderer\ConsoleOutputBoardRenderer;
use Ulrichsg\Getopt;

/**
 * Prints boards to the console.
 */
class ConsoleOutput extends BaseOutput
{
    // Attributes

    /**
     * The time for that one game step will be displayed in the console in microseconds
     *
     * @var int $stepDisplayTimeInMicroseconds
     */
    private $stepDisplayTimeInMicroseconds;


    // Magic Methods

    /**
     * ConsoleOutput constructor.
     */
    public function __construct()
    {
        parent::__construct("CONSOLE OUTPUT");
        $this->stepDisplayTimeInMicroseconds = 50 * 1000;
    }


    // Class Methods

    /**
     * Adds output specific options to the option list.
     *
     * @param Getopt $_options The option list
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "consoleOutputStepTime", Getopt::REQUIRED_ARGUMENT, "The time for that one game step will be displayed in the console in milliseconds (Default: 50)\n")
            )
        );
    }

    /**
     * Initializes the output.
     *
     * @param Getopt $_options The option list
     * @param Board $_board The initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
	    $this->boardRenderer = new ConsoleOutputBoardRenderer($_board);

	    if ($_options->getOption("consoleOutputStepTime") !== null)
        {
            $this->stepDisplayTimeInMicroseconds = (int)$_options->getOption("consoleOutputStepTime") * 1000;
        }
    }

    /**
     * Outputs one board.
     *
     * @param Board $_board The current board
     * @param int $_gameStep The current game step
     */
    public function outputBoard(Board $_board, int $_gameStep)
    {
        $this->shellOutputHelper->moveCursorToTopLeftCorner();
        $this->printTitle();

        $gameStepString = "Game step: " . $_gameStep . "\n";
        $boardContentString = $this->boardRenderer->renderBoard($_board);
        $this->shellOutputHelper->printCenteredOutputString($gameStepString);
        $this->shellOutputHelper->printCenteredOutputString($boardContentString);

        if ($this->stepDisplayTimeInMicroseconds > 0) usleep($this->stepDisplayTimeInMicroseconds);
    }

    /**
     * Finishes the output.
     * This method displays that the simulation is finished, writes files and deletes temporary files (if necessary).
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     */
    public function finishOutput(String $_simulationEndReason)
    {
        parent::finishOutput($_simulationEndReason);
        $this->shellOutputHelper->moveCursorToBottomLeftCorner();
    }
}
