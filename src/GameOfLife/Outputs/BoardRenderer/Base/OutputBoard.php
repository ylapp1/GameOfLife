<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;

use Output\BoardPrinter\OutputBoard\BorderPartBuilder\BaseBorder;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\BaseBorderPart;

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
	 * @var TextBorderRenderer $borderSymbolGrid
	 */
	private $borderPrinter;

	private $cachedBorderSymbolRows;

	private $baseBorder;


	public function hasCachedBorders()
	{
		if ($this->cachedBorderSymbolRows) return true;
		else return false;
	}


    // Magic Methods

    /**
     * OutputBoard constructor.
     */
    public function __construct()
    {
    	$this->cellSymbolGrid = new SymbolGrid();
    	$this->borderPrinter = new TextBorderRenderer();
    }


    // Class Methods

	/**
	 * Resets the symbol and border symbol grid.
	 */
    public function reset()
    {
        $this->cellSymbolGrid->reset();
        $this->borderPrinter->resetBorders();
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
	 * @param BaseBorderPart $_border The border
	 */
    public function addBorderPart(BaseBorderPart $_border)
    {
    	$this->borderPrinter->addBorderPart($_border);
    }

	/**
	 * Returns the row strings of this output board.
	 *
	 * @return String[] The row strings of this output board
	 */
    public function getRowStrings(): array
    {
    	$borderSymbolGrid = new SymbolGrid();

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
