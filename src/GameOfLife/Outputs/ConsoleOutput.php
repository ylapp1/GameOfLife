<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use GameOfLife\Field;
use Ulrichsg\Getopt;
use Utils\Shell\ShellInformationFetcher;
use Utils\Shell\ShellOutputHelper;

/**
 * Prints boards to the console.
 */
class ConsoleOutput extends BaseOutput
{
    /**
     * The shell output helper
     *
     * @var ShellOutputHelper $shellOutputHelper
     */
    private $shellOutputHelper;

    /**
     * The shell information fetcher
     *
     * @var ShellInformationFetcher $shellInformationFetcher
     */
    private $shellInformationFetcher;

    /**
     * The time for which the program will sleep between each game step in milliseconds
     *
     * @var int $sleepTime
     */
    private $sleepTime;

    /**
     * Contains the number new lines for outputBoard()
     *
     * @var int $numberOfNewLinesOutputBoard
     */
    private $numberOfNewLinesOutputBoard;

    /**
     * Contains the number of new lines for finishOutput()
     *
     * @var int $numberOfNewLinesFinishOutput
     */
    private $numberOfNewLinesFinishOutput;


    /**
     * ConsoleOutput constructor.
     */
    public function __construct()
    {
        $this->shellOutputHelper = new ShellOutputHelper();
        $this->shellInformationFetcher = new ShellInformationFetcher();
        $this->sleepTime = 100;
        $this->numberOfNewLinesOutputBoard = 0;
        $this->numberOfNewLinesFinishOutput = 0;
    }


    /**
     * Adds ConsoleOutputs specific options to a Getopt object.
     *
     * @param Getopt $_options The option list to which the options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(array(
                array(
                    null,
                    "consoleOutputSleepTime",
                    Getopt::REQUIRED_ARGUMENT,
                    "The time for which the program will sleep between each game step in milliseconds (Default: 0.1 seconds)\n"
                )
            )
        );
    }

    /**
     * Initializes the output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        if ($_options->getOption("consoleOutputSleepTime") !== null)
        {
            $this->sleepTime = (int)$_options->getOption("consoleOutputSleepTime");
        }

        // +5 is because: 2x border, 1x gamestep, 2x empty line
        $this->numberOfNewLinesOutputBoard = $this->shellInformationFetcher->getNumberOfShellLines() - ($_board->height() + 5);

        // Subtract 4 new lines because 1x simulation finished, 3x empty lines
        $this->numberOfNewLinesFinishOutput = $this->numberOfNewLinesOutputBoard - 4;

        if (stristr(PHP_OS, "linux")) echo str_repeat("\n", $this->shellInformationFetcher->getNumberOfShellLines());
    }

    /**
     * Outputs one game step.
     *
     * @param Board $_board Current board
     * @param Bool $_isFinalBoard Indicates whether the simulation ends after this output
     */
    public function outputBoard(Board $_board, Bool $_isFinalBoard)
    {
        $this->shellOutputHelper->clearScreen();

        echo $this->getBoardTitleString($_board->width(), $_board->gameStep());
        echo $this->getBoardContentString($_board, "║", "☻", " ");

        if (! $_isFinalBoard)
        {
            echo str_repeat("\n", $this->numberOfNewLinesOutputBoard);
            usleep($this->sleepTime * 1000);
        }
    }

    /**
     * Finish output (Display that simulation is finished, write files and delete temporary files).
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     */
    public function finishOutput(String $_simulationEndReason)
    {
        parent::finishOutput($_simulationEndReason);

        $additionalNewLine = "";
        if (stristr(PHP_OS, "linux")) $additionalNewLine = "\n";

        echo str_repeat("\n", $this->numberOfNewLinesFinishOutput) . $additionalNewLine;
    }

    /**
     * Returns the board output string.
     *
     * @param Board $_board The board
     * @param String $_sideBorderSymbol Symbol for left and right border
     * @param String $_cellAliveSymbol Symbol for a living cell
     * @param String $_cellDeadSymbol Symbol for a dead cell
     *
     * @return String Board output string
     */
    protected function getBoardContentString(Board $_board, String $_sideBorderSymbol, String $_cellAliveSymbol, String $_cellDeadSymbol): String
    {
        $output =  $this->getHorizontalLineString($_board->width(), "╔", "╗", "═") . "\n";

        for ($y = 0; $y < $_board->height(); $y++)
        {
            $output .= $_sideBorderSymbol;
            $output .= $this->getRowOutputString($_board->fields()[$y], $_cellAliveSymbol, $_cellDeadSymbol);
            $output .= $_sideBorderSymbol . "\n";
        }

        $output .= $this->getHorizontalLineString($_board->width(), "╚", "╝", "═") . "\n";

        return $output;
    }

    /**
     * Returns the title string of the board (the current game step with padding).
     *
     * @param int $_boardWidth The board width
     * @param int $_gameStep The current game step
     *
     * @return String The title string
     */
    private function getBoardTitleString(int $_boardWidth, int $_gameStep): String
    {
        $output = "Game step: " . ($_gameStep + 1);
        $paddingLeft = ceil(($_boardWidth - strlen($output)) / 2) + 1;

        $padding = str_pad("", $paddingLeft);
        $output = $padding . $output . "\n";

        return $output;
    }

    /**
     * Returns an output string for either the upper or bottom border of the board.
     *
     * @param int $_length Length of the line (not including left and right edge symbol)
     * @param String $_leftEdgeSymbol The symbol for the left edge of the line
     * @param String $_rightEdgeSymbol The symbol for the right edge of the line
     * @param String $_lineSymbol The symbol for the line itself
     * @param array $_specialSymbols The list of special symbols in the format array(position => symbol)
     *
     * @return String Line output string
     */
    protected function getHorizontalLineString(int $_length, String $_leftEdgeSymbol, String $_rightEdgeSymbol, String $_lineSymbol, array $_specialSymbols = null): String
    {
        $output = $_leftEdgeSymbol;
        for ($x = 0; $x < $_length; $x++)
        {
            if (isset($_specialSymbols[$x])) $output .= $_specialSymbols[$x];
            else $output .= $_lineSymbol;
        }
        $output .= $_rightEdgeSymbol;

        return $output;
    }

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     * @param String $_cellAliveSymbol The symbol for living cells
     * @param String $_cellDeadSymbol The symbol for dead cells
     *
     * @return String Row output String
     */
    protected function getRowOutputString (array $_fields, String $_cellAliveSymbol, String $_cellDeadSymbol): String
    {
        $output = "";

        foreach ($_fields as $field)
        {
            if ($field->isAlive()) $output .= $_cellAliveSymbol;
            else $output .= $_cellDeadSymbol;
        }

        return $output;
    }
}
