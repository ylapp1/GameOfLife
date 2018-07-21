<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use GameOfLife\Coordinate;

/**
 * Container that stores a two dimensional list of symbols for cell and border symbols.
 * This class does not check that all rows have the same amount of symbols.
 */
class BaseSymbolGrid
{
	// Attributes

	/**
	 * The list of symbol rows
	 *
	 * @var mixed[][]
	 */
	protected $symbolRows;


	// Magic Methods

	/**
	 * BaseSymbolGrid constructor.
	 */
	public function __construct()
	{
		$this->symbolRows = array();
	}


	// Getters and Setters

	/**
	 * Returns the symbol rows.
	 *
	 * @return mixed[][] The symbol rows
	 */
	public function symbolRows(): array
	{
		return $this->symbolRows;
	}


	// Class Methods

	/**
	 * Resets the symbol rows to an empty list.
	 */
	public function reset()
	{
		$this->symbolRows = array();
	}

	/**
	 * Sets the symbol at a specific position inside the grid.
	 *
	 * @param Coordinate $_position The position
	 * @param mixed $_symbol The new symbol
	 */
	public function setSymbolAt(Coordinate $_position, $_symbol)
	{
		if (! isset($this->symbolRows[$_position->y()])) $this->symbolRows[$_position->y()] = array();
		$this->symbolRows[$_position->y()][$_position->x()] = $_symbol;
	}

	/**
	 * Adds a row of symbols to the symbol grid.
	 *
	 * @param mixed[] $_symbolRow The list of row symbols
	 */
	public function addSymbolRow(array $_symbolRow)
	{
		$this->symbolRows[] = $_symbolRow;
	}
}
