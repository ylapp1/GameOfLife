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
    // Attributes

    /**
     * The list of border symbols that are used to print the borders of the board
     *
     * @var String[] $borderSymbols
     */
    protected $borderSymbols = array(
        "top-left" => "╔",
        "top-right" => "╗",
        "bottom-right" => "╝",
        "bottom-left" => "╚",
        "top-bottom" => "═",
        "left-right" => "║"
    );

    /**
     * The symbol that is used to print a living cell
     *
     * @var String $cellAliveSymbol
     */
    protected $cellAliveSymbol = "☻";

    /**
     * The symbol that is used to print a dead cell
     *
     * @var String $cellDeadSymbol
     */
    protected $cellDeadSymbol = " ";

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
        $this->shellOutputHelper->printCenteredOutputString($gameStepString);
        $this->shellOutputHelper->printCenteredOutputString($this->getBoardContentString($_board));

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

    /**
     * Returns the board output string for one board.
     *
     * @param Board $_board The board
     *
     * @return String The board output string
     */
    protected function getBoardContentString(Board $_board): String
    {
        $borderTopString = $this->getBorderTopString($_board);
        $borderBottomString = $this->getBorderBottomString($_board);

        $boardContentString = $borderTopString . "\n";
        for ($y = 0; $y < $_board->height(); $y++)
        {
            $rowString = $this->getRowOutputString($_board->fields()[$y]);
            $boardContentString .= $rowString . "\n";
        }
        $boardContentString .= $borderBottomString . "\n";

        return $boardContentString;
    }

    /**
     * Returns the string for the top border.
     *
     * @param Board $_board The board
     *
     * @return String The string for the top border
     */
    protected function getBorderTopString($_board): String
    {
        return $this->getHorizontalLineString(
            $_board->width(), $this->borderSymbols["top-left"], $this->borderSymbols["top-right"], $this->borderSymbols["top-bottom"]
        );
    }

    /**
     * Returns the string for the bottom border.
     *
     * @param Board $_board The board
     *
     * @return String The string for the bottom border
     */
    protected function getBorderBottomString($_board): String
    {
        return $this->getHorizontalLineString(
            $_board->width(), $this->borderSymbols["bottom-left"], $this->borderSymbols["bottom-right"], $this->borderSymbols["top-bottom"]
        );
    }

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     *
     * @return String Row output String
     */
    protected function getRowOutputString (array $_fields): String
    {
        $rowString = $this->borderSymbols["left-right"];
        foreach ($_fields as $field)
        {
        	$rowString .= $this->getCellSymbol($field);
        }
        $rowString .= $this->borderSymbols["left-right"];

        return $rowString;
    }

	/**
	 * Returns the symbol for a cell.
	 *
	 * @param Field $_field
	 *
	 * @return String The symbol for the cell
	 */
    protected function getCellSymbol(Field $_field): String
    {
	    if ($_field->isAlive()) return $this->cellAliveSymbol;
	    else return $this->cellDeadSymbol;
    }

    /**
     * Returns an output string for either the upper or bottom border of the board.
     *
     * @param int $_length The length of the line (not including left and right edge symbol)
     * @param String $_leftEdgeSymbol The symbol for the left edge of the line
     * @param String $_rightEdgeSymbol The symbol for the right edge of the line
     * @param String $_lineSymbol The symbol for the line itself
     *
     * @return String The line output string
     */
    protected function getHorizontalLineString(int $_length, String $_leftEdgeSymbol, String $_rightEdgeSymbol, String $_lineSymbol): String
    {
        $output = $_leftEdgeSymbol;
        for ($x = 0; $x < $_length; $x++)
        {
            $output .= $_lineSymbol;
        }
        $output .= $_rightEdgeSymbol;

        return $output;
    }
}
