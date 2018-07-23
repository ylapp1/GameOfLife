<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;


class TextBoardFieldRenderer
{
    /**
     * @var BaseSymbolGrid $boardFieldSymbolGrid
     */
    protected $boardFieldSymbolGrid;


    public function boardFieldSymbolGrid()
    {
        return $this->boardFieldSymbolGrid;
    }

    protected function reset()
    {
        $this->boardFieldSymbolGrid->reset();
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
}