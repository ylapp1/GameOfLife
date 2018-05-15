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

/**
 * Prints boards to the console.
 */
class ConsoleOutput extends BaseOutput
{
    /**
     * The time for that one game step will be displayed in the console in milliseconds
     *
     * @var int $stepTime
     */
    private $stepTime;

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
        parent::__construct("CONSOLE OUTPUT");
        $this->stepTime = 50;
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
                    "consoleOutputStepTime",
                    Getopt::REQUIRED_ARGUMENT,
                    "The time for that one game step will be displayed in the console in milliseconds (Default: 0.05 seconds)\n"
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
        parent::startOutput($_options, $_board);

        if ($_options->getOption("consoleOutputStepTime") !== null)
        {
            $this->stepTime = (int)$_options->getOption("consoleOutputStepTime");
        }

        // +8 is because: 2x border, 1x game step, 2x empty line, 3x Title
        $this->numberOfNewLinesOutputBoard = $this->shellInformationFetcher->getNumberOfShellLines() - ($_board->height() + 8);
        if ($this->numberOfNewLinesOutputBoard < 0) $this->numberOfNewLinesOutputBoard = 0;

        // Subtract 4 new lines because 1x simulation finished, 2x empty lines, 1x Command prompt of shell
        $this->numberOfNewLinesFinishOutput = $this->numberOfNewLinesOutputBoard - 4;
        if ($this->numberOfNewLinesFinishOutput < 0) $this->numberOfNewLinesFinishOutput = 0;
    }

    /**
     * Outputs one game step.
     *
     * @param Board $_board Current board
     * @param int $_gameStep The current game step
     */
    public function outputBoard(Board $_board, int $_gameStep)
    {
        $startTimeStamp = microtime(true);

        $this->shellOutputHelper->clearScreen();
        $this->printTitle();

        $gameStepString = "Game step: " . $_gameStep . "\n";
        echo $this->shellOutputHelper->getCenteredOutputString($gameStepString);
        echo $this->getBoardContentString($_board, "║", "☻", " ");

        $secondsNeeded = microtime(true) - $startTimeStamp;
        $microsecondsNeeded = ceil(fmod($secondsNeeded, 1) * 1000000);
        $remainingSleepTimeInMilliseconds = ($this->stepTime * 1000) - $microsecondsNeeded;

        if ($remainingSleepTimeInMilliseconds > 0) usleep($remainingSleepTimeInMilliseconds);
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
        $row =  $this->getHorizontalLineString($_board->width(), "╔", "╗", "═");
        $output = $this->shellOutputHelper->getCenteredOutputString($row) . "\n";

        for ($y = 0; $y < $_board->height(); $y++)
        {
            $row = $_sideBorderSymbol . $this->getRowOutputString($_board->fields()[$y], $_cellAliveSymbol, $_cellDeadSymbol) . $_sideBorderSymbol;
            $output .= $this->shellOutputHelper->getCenteredOutputString($row) . "\n";
        }

        $row = $this->getHorizontalLineString($_board->width(), "╚", "╝", "═");
        $output .= $this->shellOutputHelper->getCenteredOutputString($row) . "\n";

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
