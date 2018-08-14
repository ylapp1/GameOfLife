<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart;

use GameOfLife\Coordinate;

/**
 * Stores the symbols and their relative positions for a rendered border part.
 */
class TextRenderedBorderPart
{
	// Attributes

	/**
	 * Stores the border symbols of this rendered border part
	 *
	 * The structure of this array is array( array(Coordinate $_position, String $_symbol) )
	 *
	 * Horizontal borders are added in extra lines between the cell symbol rows
	 * Vertical borders are added in extra columns inside the cell symbol rows
	 *
	 * @var array $borderSymbols
	 */
	private $borderSymbols;


	// Magic Methods

	/**
	 * TextRenderedBorderPart constructor.
	 */
	public function __construct()
	{
		$this->borderSymbols = array();
	}


	// Getters and Setters

	/**
	 * Returns the list of border symbols.
	 *
	 * @return array The list of border symbols
	 */
	public function borderSymbols(): array
	{
		return $this->borderSymbols;
	}


	// Class Methods

	/**
	 * Adds one border symbol to this rendered border.
	 *
	 * @param String $_symbol The border symbol
	 * @param Coordinate $_at The coordinate of the border symbol relative to the start of the border
	 * @param Bool $_isInCellSymbolRow If true, the border symbol will be added inside a cell symbol row
	 * @param Bool $_isInCellSymbolColumn If true, the border symbol will be added inside a cell symbol column
	 */
	public function addBorderSymbol(String $_symbol, Coordinate $_at, Bool $_isInCellSymbolRow, Bool $_isInCellSymbolColumn)
	{
		$xPosition = $_at->x() * 2;
		if ($_isInCellSymbolColumn) $xPosition += 1;

		$yPosition = $_at->y() * 2;
		if ($_isInCellSymbolRow) $yPosition += 1;

		$this->borderSymbols[] = array(new Coordinate($xPosition, $yPosition), $_symbol);
	}
}
