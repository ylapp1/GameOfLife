<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter;

use Output\BoardPrinter\OutputBoard\OutputBorder\OutputBorderPart;
use Output\BoardPrinter\OutputBoard\SymbolGrid\BorderSymbolGrid;
use Output\BoardPrinter\OutputBoard\SymbolGrid\SymbolGrid;

/**
 * Stores the row and border output strings of the board.
 */
class OutputBoard
{
    // Attributes

	/**
	 * The cell symbol grid
	 *
	 * @var SymbolGrid $cellSymbolGrid
	 */
	private $cellSymbolGrid;

	/**
	 * The border symbol grid
	 *
	 * @var BorderSymbolGrid $borderSymbolGrid
	 */
	private $borderSymbolGrid;


    // Magic Methods

    /**
     * OutputBoard constructor.
     */
    public function __construct()
    {
    	$this->cellSymbolGrid = new SymbolGrid();
    	$this->borderSymbolGrid = new BorderSymbolGrid();
    }


    // Class Methods

	/**
	 * Resets the symbol and border symbol grid.
	 */
    public function reset()
    {
        $this->cellSymbolGrid->reset();
        $this->borderSymbolGrid->reset();
    }

	/**
	 * Adds a row of board field symbols to the cell symbol grid.
	 *
	 * @param String[] $_boardFieldSymbolsRow The row of board field symbols
	 */
    public function addBoardFieldSymbolsRow(array $_boardFieldSymbolsRow)
    {
        $this->cellSymbolGrid->addSymbolRow($_boardFieldSymbolsRow);
    }

	/**
	 * Adds a border to this OutputBoards border grid.
	 *
	 * @param OutputBorderPart $_border The border
	 */
    public function addBorder(OutputBorderPart $_border)
    {
    	$this->borderSymbolGrid->addBorder($_border);
    }

	/**
	 * Returns the row strings of this output board.
	 *
	 * @return String[] The row strings of this output board
	 */
    public function getRowStrings(): array
    {
    	$this->borderSymbolGrid->drawBorders();

    	$cellSymbolRows = $this->cellSymbolGrid->symbolRows();
    	$borderSymbolRows = $this->borderSymbolGrid->symbolRows();

    	// Create a list of row ids
	    $rowIds = array();
	    $rowIds = array_merge($rowIds, array_keys($cellSymbolRows));
	    $rowIds = array_merge($rowIds, array_keys($borderSymbolRows));
	    $rowIds = array_unique($rowIds);
	    sort($rowIds);

        $rowStrings = array();
        foreach ($rowIds as $rowId)
        {
        	// Add borders between rows
        	if (isset($borderSymbolRows[$rowId])) $rowStrings[] = implode("", $borderSymbolRows[$rowId]);

        	if (isset($cellSymbolRows[$rowId]))
	        {
	        	$rowString = "";
	        	foreach ($cellSymbolRows[$rowId] as $x => $cellSymbol)
		        {
		        	// Add borders between columns
			        if (isset($borderSymbolRows[$rowId][$x])) $rowString .= $borderSymbolRows[$rowId][$x];
			        $rowString .= $cellSymbol;
		        }

		        $rowStrings[] = $rowString;
	        }
        }

        return $rowStrings;
    }
}
