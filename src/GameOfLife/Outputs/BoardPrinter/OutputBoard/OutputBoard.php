<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;

use Output\BoardPrinter\OutputBoard\OutputBorderPart\OutputBorderPart;
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
    public function addBorderPart(OutputBorderPart $_border)
    {
    	$this->borderSymbolGrid->addBorderPart($_border);
    }

	/**
	 * Returns the row strings of this output board.
	 *
	 * @param int $_boardWidth The number of fields per row
	 *
	 * @return String[] The row strings of this output board
	 */
    public function getRowStrings(int $_boardWidth): array
    {
    	$this->borderSymbolGrid->drawBorders($_boardWidth);

    	$cellSymbolRows = $this->cellSymbolGrid->symbolRows();
    	$borderSymbolRows = $this->borderSymbolGrid->symbolRows();

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
	        	$rowString = "";
	        	foreach ($cellSymbolRows[$y] as $x => $cellSymbol)
		        {
		        	// Add borders between columns
			        if ($borderSymbolRowIsSet && isset($borderSymbolRows[$borderSymbolRowIndex][$x]))
			        {
			        	$rowString .= $borderSymbolRows[$borderSymbolRowIndex][$x];
			        }
			        $rowString .= $cellSymbol;
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
