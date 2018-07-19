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
use Output\BoardPrinter\BorderPartBuilder\BaseBorderPartBuilder;
use Output\BoardPrinter\OutputBoard\OutputBoard;

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
     * The border
     *
     * @var BaseBorderPartBuilder $border
     */
    protected $border;

    /**
     * The output board
     *
     * @var OutputBoard $outputBoard
     */
    private $outputBoard;


    // Magic Methods

    /**
     * BaseBoardPrinter constructor.
     *
     * @param String $_cellAliveSymbol The symbol that is used to print a living cell
     * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
     * @param BaseBorderPartBuilder $_border The border
     */
    protected function __construct(String $_cellAliveSymbol, String $_cellDeadSymbol, BaseBorderPartBuilder $_border)
    {
        $this->cellAliveSymbol = $_cellAliveSymbol;
        $this->cellDeadSymbol = $_cellDeadSymbol;
        $this->border = $_border;
        $this->outputBoard = new OutputBoard();
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
        $this->outputBoard->reset();

        for ($y = 0; $y < $_board->height(); $y++)
        {
            $rowOutputSymbols = $this->getRowOutputSymbols($_board->fields()[$y]);
            $this->outputBoard->addBoardFieldSymbolsRow($rowOutputSymbols);
        }

	    if ($this->outputBoard->hasCachedBorders() == false) $this->border->addBordersToOutputBoard($this->outputBoard);

        return implode("\n", $this->outputBoard->getRowStrings($_board->width())) . "\n";
    }

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     *
     * @return String[] The output symbols for the cells of the row
     */
    protected function getRowOutputSymbols(array $_fields): array
    {
        $rowOutputSymbols = array();
        foreach ($_fields as $field)
        {
            $rowOutputSymbols[] = $this->getCellSymbol($field);
        }

        return $rowOutputSymbols;
    }

    /**
     * Returns the symbol for a cell in a field.
     *
     * @param Field $_field The field
     *
     * @return String The symbol for the cell in the field
     */
    protected function getCellSymbol(Field $_field): String
    {
        if ($_field->isAlive()) return $this->cellAliveSymbol;
        else return $this->cellDeadSymbol;
    }
}
