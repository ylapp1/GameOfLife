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