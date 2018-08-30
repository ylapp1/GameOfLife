<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use BoardRenderer\Base\BaseCanvas;

/**
 * Combines arrays of symbols to a string.
 */
class TextCanvas extends BaseCanvas
{
	/**
	 * Returns the output string that represents the drawn borders and cell symbols.
	 *
	 * @param int $_fieldSize The height/width of a single field in symbols
	 *
	 * @return String The output string
	 */
    public function render(int $_fieldSize)
    {
	    $totalGridRows = $this->getTotalGrid($_fieldSize);

	    $totalGridString = "\n";
	    foreach ($totalGridRows as $totalGridRow)
	    {
	    	$totalGridString .= implode("", $totalGridRow) . "\n";
	    }

	    return $totalGridString;
    }

	/**
	 * Generates and returns the total grid from the border and board field symbol grids.
	 *
	 * @param int $_fieldSize The height/width of a single field in symbols
	 *
	 * @return String[][] The total grid
	 */
    private function getTotalGrid(int $_fieldSize)
    {
    	// TODO: Apply field size to rendered board fields

    	// Render the border grid
	    $renderedBorderGrid = $this->getRenderedBorderGrid($_fieldSize);

    	$highestRowId = $this->getHighestRowId();
	    $highestColumnId = $this->getHighestColumnId();

	    $totalGridRows = array();
	    for ($y = 0; $y <= $highestRowId; $y++)
	    {
	    	$borderSymbolRowIndex = $y * 2;

		    // Add borders between rows
		    if (isset($renderedBorderGrid[$borderSymbolRowIndex])) $totalGridRows[] = $renderedBorderGrid[$borderSymbolRowIndex];

		    // Add cell symbol rows
		    if (isset($this->renderedBoardFields[$y]))
		    {
		    	$totalGridRow = array();
		    	$boardFieldSymbolRow = $this->renderedBoardFields[$y];
		    	$borderSymbolRow = array();

		    	if (isset($renderedBorderGrid[$borderSymbolRowIndex + 1]))
			    {
			    	$borderSymbolRow = $renderedBorderGrid[$borderSymbolRowIndex + 1];
			    }

		    	for ($x = 0; $x <= $highestColumnId; $x++)
			    {
			    	$borderSymbolColumnIndex = $x * 2;

				    // Add borders to the left
				    if (isset($borderSymbolRow[$borderSymbolColumnIndex]))
				    {
				    	$totalGridRow[] = $borderSymbolRow[$borderSymbolColumnIndex];
				    }

				    // Add cell symbol
				    if (isset($boardFieldSymbolRow[$x])) $totalGridRow[] = $boardFieldSymbolRow[$x];
			    }

			    $totalGridRows[] = $totalGridRow;
		    }
	    }

	    return $totalGridRows;
    }

	/**
	 * Returns the highest row id from the board field and border grids.
	 *
	 * @return mixed|null The highest row id or null if there are no rows
	 */
    private function getHighestRowId()
    {
	    // Find the highest board field row id
	    $boardFieldRowIds = array_keys($this->renderedBoardFields);
	    natsort($boardFieldRowIds);
	    $highestBoardFieldRowId = array_pop($boardFieldRowIds);

	    // TODO: Does this work??
	    $highestBorderSymbolRowId = $this->borderGrid->borderPositionsGrid()->getHighestRowId();

	    $highestRowId = null;
	    if ($highestBoardFieldRowId) $highestRowId = $highestBoardFieldRowId;
	    if ($highestBorderSymbolRowId)
	    {
	    	if (! $highestRowId || $highestBorderSymbolRowId > $highestRowId)
		    {
		    	$highestRowId = $highestBorderSymbolRowId;
		    }
	    }

	    return $highestRowId;
    }

	/**
	 * Returns the highest column id from the board field and border grids.
	 *
	 * @return mixed|null The highest column id or null if there are no columns
	 */
    private function getHighestColumnId()
    {
	    $columnIds = array();
	    foreach ($this->renderedBoardFields as $boardFieldSymbolRow)
	    {
		    $columnIds = array_merge($columnIds, array_keys($boardFieldSymbolRow));
	    }
	    natsort($columnIds);
	    $highestBoardFieldColumnId = array_pop($columnIds);

	    // TODO: Does this work??
	    $highestBorderSymbolColumnId = $this->borderGrid->borderPositionsGrid()->getHighestColumnId();

	    $highestColumnId = null;
	    if ($highestBoardFieldColumnId) $highestColumnId = $highestBoardFieldColumnId;
	    if ($highestBorderSymbolColumnId)
	    {
		    if (! $highestColumnId || $highestBorderSymbolColumnId > $highestColumnId)
		    {
			    $highestColumnId = $highestBorderSymbolColumnId;
		    }
	    }

	    return $highestColumnId;
    }
}
