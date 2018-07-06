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
use Output\BoardPrinter\BorderPrinter\BoardBorderPrinter;

class ConsoleOutputBoardPrinter extends BaseBoardPrinter
{
    /**
     * ConsoleOutputBoardPrinter constructor.
     */
    public function __construct()
    {
        parent::__construct("☻", " ", new BoardBorderPrinter());
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
        return $this->borderPrinter->getBorderTopString($_board);
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
        return $this->borderPrinter->getBorderBottomString($_board);
    }

    /**
     * Returns the output string for the cells of a single row.
     *
     * @param Field[] $_fields The fields of the row
     * @param int $_y The Y-Coordinate of the row
     *
     * @return String Row output String
     */
    protected function getRowOutputString(array $_fields, int $_y): String
    {
        $rowString = "";
        foreach ($_fields as $field)
        {
            $rowString .= $this->getCellSymbol($field);
        }
        $rowString = $this->borderPrinter->addBordersToRowString($rowString, $_y);

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
}
