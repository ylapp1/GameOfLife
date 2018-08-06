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
use Output\BoardRenderer\Text\BorderPart\TextRenderedBorderPart;

/**
 * Canvas on which borders and cells can be drawn.
 * This class uses text symbols to draw borders and cells.
 */
class TextCanvas extends BaseCanvas
{
	// Attributes

	/**
	 * The border symbol grid
	 * The grid has two rows per cell symbol row, one for the border above the row and the other for the borders inside the row.
	 * It also has two columns per cell column, one for the border left and the other for the cell itself.
	 *
	 * @var String[][] $borderSymbolGrid
	 */
    private $borderSymbolGrid;

	/**
	 * The board field symbol grid
	 *
	 * @var String[][] $boardFieldSymbolGrid
	 */
    private $boardFieldSymbolGrid;


    // Magic Methods

	/**
	 * TextCanvas constructor.
	 */
    public function __construct()
    {
    	$this->borderSymbolGrid = array();
    	$this->boardFieldSymbolGrid = array();
    }


	// Class Methods

	/**
	 * Resets the cached board field symbols.
	 *
	 * @param Bool $_resetBorders If true, the cached border symbols will be reset too
	 */
	public function reset(Bool $_resetBorders = false)
	{
		if ($_resetBorders) $this->borderSymbolGrid = array();
		$this->boardFieldSymbolGrid = array();
	}

	/**
	 * Adds a rendered border part to the border symbol grid at a specific position.
	 *
	 * @param TextRenderedBorderPart $_renderedBorder The rendered border part
	 * @param Coordinate $_at The start position of the rendered border part
	 */
    public function addRenderedBorderAt($_renderedBorder, Coordinate $_at)
    {
    	foreach ($_renderedBorder->borderSymbols() as $borderSymbolData)
	    {
	    	/** @var Coordinate $borderSymbolPosition */
	    	$borderSymbolPosition = $borderSymbolData[0];
	    	$borderSymbol = $borderSymbolData[1];

	    	$xPosition = $_at->x() + $borderSymbolPosition->x();
	    	$yPosition = $_at->y() + $borderSymbolPosition->y();

	    	if (! $this->borderSymbolGrid[$yPosition]) $this->borderSymbolGrid[$yPosition] = array();
	    	$this->borderSymbolGrid[$yPosition][$xPosition] = $borderSymbol;
	    }
    }

	/**
	 * Adds a rendered board field symbol to the board field symbol grid at a specific position.
	 *
	 * @param String $_renderedBoardField The rendered board field
	 * @param Coordinate $_at The position of the rendered board field
	 */
    public function addRenderedBoardFieldAt($_renderedBoardField, Coordinate $_at)
    {
    	if (! $this->boardFieldSymbolGrid[$_at->y()]) $this->boardFieldSymbolGrid[$_at->y()] = array();
    	$this->boardFieldSymbolGrid[$_at->y()][$_at->x()] = $_renderedBoardField;
    }

	/**
	 * Returns the output string that represents the drawn borders and cell symbols.
	 *
	 * @return String The output string
	 */
    public function getContent()
    {
	    $this->reset();
	    $this->renderBorderSymbolGrid();

	    // Find the highest row id
	    $rowIds = array_merge(array_keys($this->boardFieldSymbolGrid), array_keys($this->borderSymbolGrid));
	    natsort($rowIds);
	    $highestRowId = array_pop($rowIds);

	    // Find the highest column id
	    $columnIds = array();
	    foreach ($this->boardFieldSymbolGrid as $boardFieldSymbolRow)
	    {
	    	$columnIds = array_merge($columnIds, array_keys($boardFieldSymbolRow));
	    }
	    foreach ($this->borderSymbolGrid as $borderSymbolRow)
	    {
	    	$columnIds = array_merge($columnIds, array_keys($borderSymbolRow));
	    }
	    natsort($columnIds);
	    $highestColumnId = array_pop($columnIds);

	    $rowStrings = array();
	    for ($y = 0; $y <= $highestRowId; $y++)
	    {
	    	$cellSymbolRow = $this->boardFieldSymbolGrid[$y];
		    $borderSymbolRowIndex = $y * 2;

		    // Add borders between rows
		    if (isset($this->borderSymbolGrid[$borderSymbolRowIndex]))
		    {
			    $rowStrings[] = $this->getBorderRowString($borderSymbolRowIndex);
		    }

		    // Add cell symbol rows
		    if (isset($cellSymbolRow)) $rowStrings[] = $this->getCellSymbolRowString($y, $highestColumnId);
	    }

	    return implode("\n", $rowStrings) . "\n";
    }

	/**
	 * Fills the gaps in the border symbol grid that exist because of vertical borders.
	 */
    private function renderBorderSymbolGrid()
    {
	    // Find the lowest and highest column ids
	    $columnIds = array();
	    foreach ($this->borderSymbolGrid as $borderSymbolRow)
	    {
		    $columnIds = array_merge($columnIds, array_keys($borderSymbolRow));
	    }
	    natsort($columnIds);
	    $lowestColumnId = array_shift($columnIds);
	    $highestColumnId = array_pop($columnIds);

	    for ($x = $lowestColumnId; $x <= $highestColumnId; $x++)
	    {
		    // TODO: Fix this, determine which stuff is outer border
		    $columnContainsBorderSymbol = $this->columnContainsBorderSymbol($x);
		    $isBorderColumn = ($x % 2 == 0);

		    foreach ($this->borderSymbolGrid as $y => $borderSymbolRow)
	    	{
	    		$isBorderRow = ($y % 2 == 0);

	    		if 
			    if (! $isBorderColumn || $columnContainsBorderSymbol)
			    {
			    	$this->borderSymbolGrid[$y][$x] = " ";
			    }

			    // TODO: Auto complete borders
		    }

		    ksort($this->borderSymbolGrid[$y]);
	    }
    }

	/**
	 * Returns whether a specific column contains any border symbols.
	 *
	 * @param int $_x The X-Position of the column
	 *
	 * @return Bool True if the column contains a border symbol, false otherwise
	 */
	private function columnContainsBorderSymbol(int $_x): Bool
	{
		foreach ($this->borderSymbolGrid as $y => $borderSymbolRow)
		{
			if ($borderSymbolRow[$_x]) return true;
		}

		return false;
	}

	/**
	 * Returns the string for a border row.
	 *
	 * @param int $_y The Y-Coordinate of th border row
	 *
	 * @return String The border row string
	 */
    private function getBorderRowString(int $_y): String
    {
    	$rowString = implode("", $this->borderSymbolGrid[$_y]);

    	// TODO: Fill gaps with empty space before returning

	    return $rowString;
    }

    private function getCellSymbolRowString(int $_y, int $_highestColumnId)
    {
	    $borderSymbolRowIsSet = isset($borderSymbolRows[$borderSymbolRowIndex]);
	    $borderSymbolRowIndex = $_y * 2 + 1;
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
    }
}
