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
use Output\BoardPrinter\Border\OuterBorder\BoardBorder;

/**
 * The BoardPrinter for the ConsoleOutput.
 */
class ConsoleOutputBoardPrinter extends BaseBoardPrinter
{
	// Magic Methods

    /**
     * ConsoleOutputBoardPrinter constructor.
     *
     * @param Board $_board The board to which this board printer belongs
     */
    public function __construct(Board $_board)
    {
        parent::__construct("☻", " ", new BoardBorder($_board));
    }


    // Class Methods

	/**
	 * Returns the string for the top border.
	 *
	 * @return String The string for the top border
	 */
	protected function getBorderTopString(): String
    {
        return $this->border->getBorderTopString();
    }

	/**
	 * Returns the string for the bottom border.
	 *
	 * @return String The string for the bottom border
	 */
	protected function getBorderBottomString(): String
    {
        return $this->border->getBorderBottomString();
    }

	/**
	 * Returns the output string for the cells of a single row.
	 *
	 * @param Field[] $_fields The fields of the row
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return String The output string for the cells of the row
	 */
	protected function getRowOutputString(array $_fields, int $_y): String
    {
        $rowString = "";
        foreach ($_fields as $field)
        {
            $rowString .= $this->getCellSymbol($field);
        }
        $rowString = $this->border->addBordersToRowString($rowString, $_y);

        return $rowString;
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
