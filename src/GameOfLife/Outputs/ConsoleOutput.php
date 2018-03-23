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
use Utils\ShellExecutor;

/**
 * Prints boards to the console.
 */
class ConsoleOutput extends BaseOutput
{
    /**
     * The shell executor
     *
     * @var ShellExecutor $shellExecutor
     */
    private $shellExecutor;


    /**
     * ConsoleOutput constructor.
     */
    public function __construct()
    {
        $this->shellExecutor = new ShellExecutor(PHP_OS);
    }


    /**
     * Initializes the output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        echo "\nStarting the simulation ...\n";
    }

    /**
     * Outputs one game step.
     *
     * @param Board $_board Current board
     */
    public function outputBoard(Board $_board)
    {
        $this->shellExecutor->clearScreen();

        echo "\n\n";

        echo $this->getBoardTitleString($_board->width(), $_board->gameStep());
        echo $this->getBoardContentString($_board, "║", "☻", " ");

        // wait for 0.1 seconds before printing the next board
        usleep(100000);
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
