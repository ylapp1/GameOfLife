<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use GameOfLife\Field;

abstract class BaseBoardFieldRenderer
{
    /**
     * @var BaseSymbolGrid $boardFieldSymbolGrid
     */
    protected $boardFieldSymbolGrid;


    public function boardFieldSymbolGrid()
    {
        return $this->boardFieldSymbolGrid;
    }

    public function reset()
    {
        $this->boardFieldSymbolGrid->reset();
    }

    /**
     * @param Field[][] $_boardFieldRows
     */
    public function renderBoardFields($_boardFieldRows)
    {
        foreach ($_boardFieldRows as $boardFieldRow)
        {
            $rowOutputSymbols = $this->getRowOutputSymbols($boardFieldRow);
            $this->boardFieldSymbolGrid->addSymbolRow($rowOutputSymbols);
        }
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
     * @param Bool $_cellState The cell state (alive or dead)
     *
     * @return mixed The symbol for the cell in the field
     */
    abstract protected function getCellSymbol(Bool $_cellState);
}
