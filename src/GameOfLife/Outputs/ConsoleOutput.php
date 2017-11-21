<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
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
        echo "\n\n";

        echo $this->getBoardTitleString($_board->width(), $_board->gameStep());
        echo $this->getBoardContentString($_board, "║", "☻", " ");

        // wait for 0.1 seconds before printing the next board
        usleep(100000);
    }

    /**
     * Prints a message saying that the simulation is finished.
     */
    public function finishOutput()
    {
        echo "\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n";
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
    private function getBoardContentString(Board $_board, String $_sideBorderSymbol, String $_cellAliveSymbol, String $_cellDeadSymbol)
    {
        $output =  $this->getHorizontalBorderString($_board->width(), "╔", "╗") . "\n";

        for ($y = 0; $y < $_board->height(); $y++)
        {
            $output .= $_sideBorderSymbol;
            $output .= $this->getRowOutputString($_board->fields()[$y], $_cellAliveSymbol, $_cellDeadSymbol);
            $output .= $_sideBorderSymbol . "\n";
        }

        $output .= $this->getHorizontalBorderString($_board->width(), "╚", "╝") . "\n";

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
    private function getBoardTitleString(int $_boardWidth, int $_gameStep)
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
     * @param int $_boardWidth Width of the board
     * @param String $_leftCornerSymbol The symbol for the left corner
     * @param String $_rightCornerSymbol The symbol for the right corner
     *
     * @return String Border output string
     */
    private function getHorizontalBorderString(int $_boardWidth, String $_leftCornerSymbol, String $_rightCornerSymbol)
    {
        $output = $_leftCornerSymbol;
        for ($x = 0; $x < $_boardWidth; $x++)
        {
            $output .= "═";
        }
        $output .= $_rightCornerSymbol;

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
    public function getRowOutputString (array $_fields, String $_cellAliveSymbol, String $_cellDeadSymbol)
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