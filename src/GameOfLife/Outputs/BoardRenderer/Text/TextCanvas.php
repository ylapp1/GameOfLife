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


    // Getters and Setters

	/**
	 * Returns the border symbol grid.
	 *
	 * @return String[][] The border symbol grid
	 */
	public function borderSymbolGrid(): array
	{
		return $this->borderSymbolGrid;
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
		    if (isset($this->borderSymbolGrid[$borderSymbolRowIndex])) $rowStrings[] = $this->getBorderRowString($borderSymbolRowIndex);

		    // Add cell symbol rows
		    if (isset($cellSymbolRow)) $rowStrings[] = $this->getCellSymbolRowString($y, $highestColumnId);
	    }

	    return implode("\n", $rowStrings) . "\n";
    }

	/**
	 * Returns the string for a border row.
	 *
	 * @param int $_y The Y-Coordinate of the border row
	 *
	 * @return String The border row string
	 */
    private function getBorderRowString(int $_y): String
    {
    	$rowString = implode("", $this->borderSymbolGrid[$_y]);
	    return $rowString;
    }

	/**
	 * Returns the string for a cell symbol row including the vertical borders between the cells.
	 *
	 * @param int $_y The Y-Coordinate of the cell symbol row
	 * @param int $_highestColumnId The highest column id of all rows
	 *
	 * @return String The cell symbol row string
	 */
    private function getCellSymbolRowString(int $_y, int $_highestColumnId): String
    {
	    $borderSymbolRowIndex = $_y * 2 + 1;
	    $borderSymbolColumnIndex = 0;

	    $borderSymbolRow = $this->borderSymbolGrid[$borderSymbolRowIndex];
	    $cellSymbolRow = $this->boardFieldSymbolGrid[$_y];

	    $borderSymbolRowIsSet = isset($borderSymbolRow);

	    $rowString = "";
	    for ($x = 0; $x < $_highestColumnId; $x++)
	    {
		    // Add borders between columns
		    if ($borderSymbolRowIsSet && isset($borderSymbolRow[$borderSymbolColumnIndex]))
		    {
			    $rowString .= $borderSymbolRow[$borderSymbolColumnIndex];
		    }

		    // Add the cell symbol
		    if (isset($cellSymbolRow[$x])) $rowString .= $cellSymbolRow[$x];

		    $borderSymbolColumnIndex += 2;
	    }

	    return $rowString;
    }
}
