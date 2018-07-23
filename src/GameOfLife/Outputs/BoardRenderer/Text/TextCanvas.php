<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\BaseCanvas;

class TextCanvas extends BaseCanvas
{
    private $borderSymbolGrid;
    private $boardFieldSymbolGrid;

    private $totalGrid;

    public function doIt()
    {
        $rowOutputSymbols = $this->getRowOutputSymbols($boardFieldRow);
        $this->boardFieldSymbolGrid->addSymbolRow($rowOutputSymbols);
    }

    public function addRenderedBorderAt($_renderedBorder, Coordinate $_at)
    {
        // TODO: Implement addRenderedBorderAt() method.
    }

    public function addRenderedBoardFieldAt($_renderedBoardField, Coordinate $_at)
    {
        // TODO: Save in board field symbol grid
        // TODO: Implement addRenderedBoardFieldAt() method.
    }

    public function getContent()
    {
        // TODO: Implement getContent() method.
    }

    public function reset()
    {
        // TODO: Implement reset() method.
    }

    // TODO: Merge the border and board field symbol grids

    /**
     * Returns the board output string for one board.
     *
     * @param Board $_board The board
     *
     * @return String The board output string
     */
    public function getBoardContentString(Board $_board): String
    {
        $this->reset();

        for ($y = 0; $y < $_board->height(); $y++)
        {
            $rowOutputSymbols = $this->getRowOutputSymbols($_board->fields()[$y]);
            $this->outputBoard->addBoardFieldSymbolsRow($rowOutputSymbols);
        }

        if ($this->outputBoard->hasCachedBorders() == false) $this->outputBoard->addBordersToOutputBoard($this->outputBoard);

        return implode("\n", $this->outputBoard->getRowStrings()) . "\n";
    }

    /**
     * Returns the row strings of this output board.
     *
     * @return String[] The row strings of this output board
     */
    public function getRowStrings(): array
    {
        $borderSymbolGrid = new BaseSymbolGrid();

        if (! $this->cachedBorderSymbolRows)
        {
            $this->borderPrinter->drawBorders($borderSymbolGrid);
            $this->cachedBorderSymbolRows = $borderSymbolGrid->symbolRows();
        }

        $cellSymbolRows = $this->cellSymbolGrid->symbolRows();
        $borderSymbolRows = $this->cachedBorderSymbolRows;

        // Create a list of row ids
        /*
        $rowIds = array();
        $rowIds = array_merge($rowIds, array_keys($cellSymbolRows));
        $rowIds = array_merge($rowIds, array_keys($borderSymbolRows));
        $rowIds = array_unique($rowIds);
        sort($rowIds);
        */

        $rowStrings = array();

        $borderSymbolRowIndex = 0;
        foreach ($cellSymbolRows as $y => $cellSymbolRow)
        {
            // Add borders between rows
            if (isset($borderSymbolRows[$borderSymbolRowIndex])) $rowStrings[] = implode("", $borderSymbolRows[$borderSymbolRowIndex]);
            $borderSymbolRowIndex++;

            if (isset($cellSymbolRows[$y]))
            {
                $borderSymbolRowIsSet = isset($borderSymbolRows[$borderSymbolRowIndex]);
                $borderSymbolColumnIndex = 0;

                $rowString = "";
                foreach ($cellSymbolRow as $x => $cellSymbol)
                {
                    // Add borders between columns
                    if ($borderSymbolRowIsSet && isset($borderSymbolRows[$borderSymbolRowIndex][$borderSymbolColumnIndex]))
                    {
                        $rowString .= $borderSymbolRows[$borderSymbolRowIndex][$borderSymbolColumnIndex];
                    }
                    $rowString .= $cellSymbol;

                    $borderSymbolColumnIndex += 2;
                }

                // Also add the border right from the board
                if ($borderSymbolRowIsSet)
                {
                    $rowColumnIds = array_keys($borderSymbolRows[$borderSymbolRowIndex]);
                    $highestRowColumnId = array_pop($rowColumnIds);

                    for ($x = $borderSymbolColumnIndex + 2; $x <= $highestRowColumnId; $x += 2)
                    {
                        if (isset($borderSymbolRows[$borderSymbolRowIndex][$x]))
                        {
                            $rowString .= $borderSymbolRows[$borderSymbolRowIndex][$x];
                        }
                    }
                }

                $rowStrings[] = $rowString;
            }
            $borderSymbolRowIndex++;
        }

        // Also add the border below the board
        if (isset($borderSymbolRows[$borderSymbolRowIndex])) $rowStrings[] = implode("", $borderSymbolRows[$borderSymbolRowIndex]);

        return $rowStrings;
    }
}
