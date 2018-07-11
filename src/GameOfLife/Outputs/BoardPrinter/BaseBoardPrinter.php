<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter;

use GameOfLife\Board;
use GameOfLife\Field;
use Output\BoardPrinter\BorderPrinter\BaseBorderPrinter;

/**
 * Parent class for board printers.
 *
 * call getBoardContentString() to get a board string
 */
abstract class BaseBoardPrinter
{
	// Attributes

    /**
     * The symbol that is used to print a living cell
     *
     * @var String $cellAliveSymbol
     */
    protected $cellAliveSymbol;

    /**
     * The symbol that is used to print a dead cell
     *
     * @var String $cellDeadSymbol
     */
    protected $cellDeadSymbol;

    /**
     * The border printer
     *
     * @var BaseBorderPrinter $borderPrinter
     */
    protected $borderPrinter;


    // Magic Methods

    /**
     * BaseBoardPrinter constructor.
     *
     * @param String $_cellAliveSymbol The symbol that is used to print a living cell
     * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
     * @param BaseBorderPrinter $_borderPrinter The border printer
     */
    protected function __construct(String $_cellAliveSymbol, String $_cellDeadSymbol, BaseBorderPrinter $_borderPrinter)
    {
        $this->cellAliveSymbol = $_cellAliveSymbol;
        $this->cellDeadSymbol = $_cellDeadSymbol;
        $this->borderPrinter = $_borderPrinter;
    }


    // Class Methods

    /**
     * Returns the board output string for one board.
     *
     * @param Board $_board The board
     *
     * @return String The board output string
     */
    public function getBoardContentString(Board $_board): String
    {
        $borderTopString = $this->getBorderTopString($_board);
        $borderBottomString = $this->getBorderBottomString($_board);

        $boardContentString = $borderTopString . "\n";
        for ($y = 0; $y < $_board->height(); $y++)
        {
            $rowString = $this->getRowOutputString($_board->fields()[$y], $y);
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
    abstract protected function getBorderTopString(Board $_board): String;

    /**
     * Returns the string for the bottom border.
     *
     * @param Board $_board The board
     *
     * @return String The string for the bottom border
     */
    abstract protected function getBorderBottomString(Board $_board): String;

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     * @param int $_y The Y-Coordinate of the row
     *
     * @return String The output string for the cells of the row
     */
    abstract protected function getRowOutputString(array $_fields, int $_y): String;
}
