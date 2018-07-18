<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\SymbolGrid;

use GameOfLife\Coordinate;

/**
 * Container that stores a two dimensional list of symbols for cell and border symbols.
 * This class does not check that all rows have the same amount of symbols.
 */
class SymbolGrid
{
	// Attributes

	/**
	 * The list of symbol rows
	 *
	 * @var String[][]
	 */
	protected $symbolRows;


	// Magic Methods

	/**
	 * SymbolGrid constructor.
	 */
	public function __construct()
	{
		$this->symbolRows = array();
	}


	// Getters and Setters

	/**
	 * Returns the symbol rows.
	 *
	 * @return String[][] The symbol rows
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
	 * Initializes a grid with empty spaces at all positions.
	 *
	 * @param int $_width The width of the grid
	 * @param int $_height The height of the grid
	 */
	protected function initializeEmptyGrid(int $_width, int $_height)
	{
		$this->reset();
		for ($y = 0; $y < $_height; $y++)
		{
			$this->symbolRows[$y] = array();
			for ($x = 0; $x < $_width; $x++)
			{
				$this->symbolRows[$y][$x] = " ";
			}
		}
	}

	/**
	 * Sets the symbol at a specific position inside the grid.
	 *
	 * @param Coordinate $_position The position
	 * @param String $_symbol The new symbol
	 */
	public function setSymbolAt(Coordinate $_position, String $_symbol)
	{
		if (! isset($this->symbolRows[$_position->y()])) $this->symbolRows[$_position->y()] = array();
		$this->symbolRows[$_position->y()][$_position->x()] = $_symbol;
	}

	/**
	 * Adds a row of symbols to the symbol grid.
	 *
	 * @param String[] $_symbolRow The list of row symbols
	 */
	public function addSymbolRow(array $_symbolRow)
	{
		$this->symbolRows[] = $_symbolRow;
	}
}
