<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;


use GameOfLife\Board;
use Output\BoardRenderer\Base\BaseBoardRenderer;

abstract class TextBoardRenderer extends BaseBoardRenderer
{
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
     * The cell symbol grid
     *
     * @var BaseSymbolGrid $cellSymbolGrid
     */
    protected $cellSymbolGrid;


    /**
     * BaseBoardPrinter constructor.
     *
     * @param String $_cellAliveSymbol The symbol that is used to print a living cell
     * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
     */
    protected function __construct(String $_cellAliveSymbol, String $_cellDeadSymbol)
    {
        $this->cellAliveSymbol = $_cellAliveSymbol;
        $this->cellDeadSymbol = $_cellDeadSymbol;
        $this->borderRenderer = new TextBorderRenderer();
        $this->cellSymbolGrid = new TextSymbolGrid();
    }


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
