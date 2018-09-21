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
 * Combines the board field and border grid symbols to a string.
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
    	// Render the border grid
	    $renderedBorderGrid = $this->getRenderedBorderGrid($_fieldSize);

	    $lowestColumnId = $this->getLowestColumnId();
	    $highestColumnId = $this->getHighestColumnId();

	    $totalGridRows = array();
	    for ($y = $this->getLowestRowId(); $y <= $this->getHighestRowId(); $y++)
	    {
	    	$borderSymbolRowIndex = $y * 2;

		    // Add borders between rows
		    if (isset($renderedBorderGrid[$borderSymbolRowIndex])) $totalGridRows[] = $renderedBorderGrid[$borderSymbolRowIndex];

		    // Add cell symbol rows
		    if (isset($this->renderedBoardFields[$y]))
		    {
		    	$totalGridRow = array();
		    	$boardFieldSymbolRow = $this->renderedBoardFields[$y];

		    	// Fetch the border symbols that are inside the current row of board fields
		    	$borderSymbolRow = array();
		    	if (isset($renderedBorderGrid[$borderSymbolRowIndex + 1]))
			    {
			    	$borderSymbolRow = $renderedBorderGrid[$borderSymbolRowIndex + 1];
			    }

		    	for ($x = $lowestColumnId; $x <= $highestColumnId; $x++)
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
	 * Returns the sorted board field row ids of the rendered board fields list.
	 *
	 * @return int[] The board field row ids
	 */
    private function getSortedBoardFieldRowIds(): array
    {
	    $boardFieldRowIds = array_keys($this->renderedBoardFields);
	    sort($boardFieldRowIds);

	    return $boardFieldRowIds;
    }

	/**
	 * Returns the sorted board field column ids of the rendered board fields list.
	 *
	 * @return int[] The sorted board field column ids
	 */
    private function getSortedBoardFieldColumnIds(): array
    {
	    $columnIds = array();
	    foreach ($this->renderedBoardFields as $boardFieldSymbolRow)
	    {
		    $columnIds = array_merge($columnIds, array_keys($boardFieldSymbolRow));
	    }
	    sort($columnIds);

	    return $columnIds;
    }

	/**
	 * Returns the lowest board field row id from the board field and border grids.
	 *
	 * @return int|null The lowest row id or null if there are no rows
	 */
    private function getLowestRowId()
    {
    	// Find the lowest board field row id
    	$sortedBoardFieldRowIds = $this->getSortedBoardFieldRowIds();

    	$lowestBoardFieldRowId = null;
    	if ($sortedBoardFieldRowIds) $lowestBoardFieldRowId = $sortedBoardFieldRowIds[0];

    	// Find the lowest border grid row id
	    $lowestBorderGridRowId = $this->borderGrid->borderPositionsGrid()->getLowestRowId();

	    $lowestRowId = $lowestBoardFieldRowId;
	    if (isset($lowestBorderGridRowId))
	    {
		    // The border grid has two rows per board field row, so the id must be divided by two
		    $lowestBorderGridRowId = ceil($lowestBorderGridRowId / 2);

		    if (! $lowestRowId || $lowestBorderGridRowId < $lowestRowId)
		    {
			    $lowestRowId = $lowestBorderGridRowId;
		    }
	    }

	    return $lowestRowId;
    }

	/**
	 * Returns the highest board field row id from the board field and border grids.
	 *
	 * @return int|null The highest row id or null if there are no rows
	 */
    private function getHighestRowId()
    {
	    // Find the highest board field row id
	    $sortedBoardFieldRowIds = $this->getSortedBoardFieldRowIds();
	    $highestBoardFieldRowId = array_pop($sortedBoardFieldRowIds);

	    // Find the highest border grid row id
	    $highestBorderGridRowId = $this->borderGrid->borderPositionsGrid()->getHighestRowId();

	    $highestRowId = $highestBoardFieldRowId;
	    if (isset($highestBorderGridRowId))
	    {
	    	// The border grid has two rows per board field row, so the id must be divided by two
	    	$highestBorderGridRowId = ceil($highestBorderGridRowId / 2);

	    	if (! $highestRowId || $highestBorderGridRowId > $highestRowId)
		    {
		    	$highestRowId = $highestBorderGridRowId;
		    }
	    }

	    return $highestRowId;
    }

	/**
	 * Returns the lowest board field column id from the board field and border grids.
	 *
	 * @return int|null The lowest column id or null if there are no rows
	 */
	private function getLowestColumnId()
	{
		// Find the lowest board field column id
		$sortedBoardFieldColumnIds = $this->getSortedBoardFieldColumnIds();

		$lowestBoardFieldColumnId = null;
		if ($sortedBoardFieldColumnIds) $lowestBoardFieldColumnId = $sortedBoardFieldColumnIds[0];

		// Find the lowest border grid row id
		$lowestBorderGridColumnId = $this->borderGrid->borderPositionsGrid()->getLowestColumnId();

		$lowestColumnId = $lowestBoardFieldColumnId;
		if (isset($lowestBorderGridColumnId))
		{
			// The border grid has two rows per board field row, so the id must be divided by two
			$lowestBorderGridColumnId = ceil($lowestBorderGridColumnId / 2);

			if (! $lowestColumnId || $lowestBorderGridColumnId < $lowestColumnId)
			{
				$lowestColumnId = $lowestBorderGridColumnId;
			}
		}


		return $lowestColumnId;
	}

	/**
	 * Returns the highest board field column id from the board field and border grids.
	 *
	 * @return int|null The highest column id or null if there are no columns
	 */
    private function getHighestColumnId()
    {
    	// Find the highest board field column id
	    $sortedBoardFieldColumnIds = $this->getSortedBoardFieldColumnIds();
	    $highestBoardFieldColumnId = array_pop($sortedBoardFieldColumnIds);

	    // Find the highest border grid column id
	    $highestBorderGridColumnId = $this->borderGrid->borderPositionsGrid()->getHighestColumnId();

	    $highestColumnId = $highestBoardFieldColumnId;
	    if (isset($highestBorderGridColumnId))
	    {
	    	// The border grid has two columns per board column, so the id must be divided by two
		    $highestBorderGridColumnId = ceil($highestBorderGridColumnId / 2);

		    if (! $highestColumnId || $highestBorderGridColumnId > $highestColumnId)
		    {
			    $highestColumnId = $highestBorderGridColumnId;
		    }
	    }

	    return $highestColumnId;
    }
}
