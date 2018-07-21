<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;


use Output\BoardRenderer\Base\BaseSymbolGrid;

class TextSymbolGrid extends BaseSymbolGrid
{
    /**
     * Initializes a grid with empty spaces at all positions.
     *
     * @param int $_width The width of the grid
     * @param int $_height The height of the grid
     */
    protected function initializeEmptyGrid(int $_width, int $_height)
    {
        $this->reset();
        for ($y = 0; $y < $_height; $y++)
        {
            $this->symbolRows[$y] = array();
            for ($x = 0; $x < $_width; $x++)
            {
                $this->symbolRows[$y][$x] = " ";
            }
        }
    }
}