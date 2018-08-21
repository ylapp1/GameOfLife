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
 * Stores the border symbols and the start/end symbol positions for a rendered border part.
 */
class TextRawRenderedBorderPart
{
	// Attributes

	/**
	 * Stores the border symbols of this rendered border part
	 *
	 * @var array $borderSymbols
	 */
	private $borderSymbols;

	/**
	 * The positions of the border symbols on the border symbol grid
	 *
	 * @var Coordinate[] $borderSymbolPositions
	 */
	private $borderSymbolPositions;


	// Magic Methods

	/**
	 * TextRawRenderedBorderPart constructor.
	 */
	public function __construct()
	{
		$this->borderSymbols = array();
		$this->borderSymbolPositions = array();
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

	/**
	 * Returns the border symbol position of this rendered border part.
	 *
	 * @return Coordinate[] The start symbol position
	 */
	public function borderSymbolPositions(): array
	{
		return $this->borderSymbolPositions;
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

		$this->borderSymbols[] = $_symbol;
		$this->borderSymbolPositions[] = new Coordinate($xPosition, $yPosition);
	}
}
