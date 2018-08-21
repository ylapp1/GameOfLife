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
 * Canvas on which borders and cells can be drawn.
 * This class uses text symbols to draw borders and cells.
 */
class TextCanvas extends BaseCanvas
{
	// Attributes

	/**
	 * The board field symbol rows
	 *
	 * @var String[][] $boardFieldSymbolRows
	 */
    private $boardFieldSymbolRows;


    // Magic Methods

	/**
	 * TextCanvas constructor.
	 *
	 * @param Bool $_cachesBorderGrid Indicates whether this canvas caches the border grid
	 */
    public function __construct(Bool $_cachesBorderGrid = true)
    {
    	$this->boardFieldSymbolRows = array();
    	parent::__construct($_cachesBorderGrid);
    }


	// Class Methods

	/**
	 * Resets the cached board field symbols.
	 */
	public function reset()
	{
		$this->boardFieldSymbolRows = array();
	}

	/**
	 * Adds the rendered board fields to the canvas.
	 *
	 * @param String[][] $_renderedBoardFields The list of rendered board fields
	 * @param int $_fieldSize The height/width of a single field in symbols
	 */
	public function addRenderedBoardFields(array $_renderedBoardFields, int $_fieldSize)
	{
		$this->boardFieldSymbolRows = $_renderedBoardFields;

		// TODO: Do something with field size
	}

	/**
	 * Returns the output string that represents the drawn borders and cell symbols.
	 *
	 * @return String The output string
	 */
    public function getContent()
    {
	    $totalGridRows = $this->getTotalGrid();
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
	 * @return String[][] The total grid
	 */
    private function getTotalGrid()
    {
    	$highestRowId = $this->getHighestRowId();
	    $highestColumnId = $this->getHighestColumnId();

	    $totalGridRows = array();
	    for ($y = 0; $y <= $highestRowId; $y++)
	    {
	    	$borderSymbolRowIndex = $y * 2;

		    // Add borders between rows
		    if (isset($this->cachedRenderedBorderGrid[$borderSymbolRowIndex])) $totalGridRows[] = $this->cachedRenderedBorderGrid[$borderSymbolRowIndex];

		    // Add cell symbol rows
		    if (isset($this->boardFieldSymbolRows[$y]) || isset($this->cachedRenderedBorderGrid[$borderSymbolRowIndex + 1]))
		    {
		    	$totalGridRow = array();

		    	$boardFieldSymbolRow = array();
		    	if (isset($this->boardFieldSymbolRows[$y]))
			    {
				    $boardFieldSymbolRow = $this->boardFieldSymbolRows[$y];
			    }

		    	$borderSymbolRow = array();
		    	if (isset($this->cachedRenderedBorderGrid[$borderSymbolRowIndex + 1]))
			    {
			    	$borderSymbolRow = $this->cachedRenderedBorderGrid[$borderSymbolRowIndex + 1];
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
				    else $totalGridRow[] = " ";
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
	    $boardFieldRowIds = array_keys($this->boardFieldSymbolRows);
	    natsort($boardFieldRowIds);
	    $highestBoardFieldRowId = array_pop($boardFieldRowIds);

	    // TODO: Does this work??
	    $highestBorderSymbolRowId = $this->borderGrid->getHighestRowId();

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
	    foreach ($this->boardFieldSymbolRows as $boardFieldSymbolRow)
	    {
		    $columnIds = array_merge($columnIds, array_keys($boardFieldSymbolRow));
	    }
	    natsort($columnIds);
	    $highestBoardFieldColumnId = array_pop($columnIds);

	    // TODO: Does this work??
	    $highestBorderSymbolColumnId = $this->borderGrid->getHighestColumnId();

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
