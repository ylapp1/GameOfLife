<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\SymbolGrid;

use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\OutputBorderPart;

/**
 * Container to store border symbols.
 */
class BorderSymbolGrid extends SymbolGrid
{
	// Attributes

	/**
	 * The list of borders
	 *
	 * @var OutputBorderPart[] $borders
	 */
	private $borders;


	// Magic Methods

	/**
	 * BorderSymbolGrid constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->borders = array();
	}


	// Class Methods

	/**
	 * Adds a border to this border symbol grid.
	 *
	 * @param OutputBorderPart $_border The border
	 */
	public function addBorderPart(OutputBorderPart $_border)
	{
		foreach ($this->borders as $border)
		{
			$border->collideWith($_border);
		}
		$this->borders[] = $_border;
	}

	/**
	 * Resets the list of borders of this border symbol grid to an empty array.
	 */
	public function resetBorders()
	{
		$this->borders = array();
	}

	/**
	 * Adds the border symbols to the symbol grid.
	 * The grid has two rows per cell symbol row, one for the border above the row and the other for the borders inside the row.
	 *
	 * @param int $_boardWidth The number of fields per row
	 */
	public function drawBorders(int $_boardWidth)
	{
		// Add the border symbols to the symbol grid
		foreach ($this->borders as $border)
		{
			$border->addBorderSymbolsToBorderSymbolGrid($this);
		}

		// Fill the border gaps with border symbols or empty space
		$lowestColumnId = 0; // TODO: Fetch lower column ids
		$highestColumnId = $_boardWidth; // TODO: Fetch highest column ids

		foreach ($this->symbolRows as $y => $symbolRow)
		{
			$rowContainsBorderSymbol = $this->rowContainsBorderSymbol($y);

			for ($x = $lowestColumnId; $x <= $highestColumnId; $x++)
			{
				if (! isset($this->symbolRows[$y][$x]))
				{
					$symbol = " ";
					if ($rowContainsBorderSymbol || $this->columnContainsBorderSymbol($x))
					{
						$gapContainingBorder = $this->getBorderContainingCoordinate(new Coordinate($x, $y));
						if ($gapContainingBorder !== null) $symbol = $gapContainingBorder->borderSymbolCenter();
					}

					$this->symbolRows[$y][$x] = $symbol;
				}
			}
		}
	}

	/**
	 * Returns whether a specific row contains any border symbols.
	 *
	 * @param int $_y The Y-Position of the row
	 *
	 * @return Bool True if the row contains a border symbol, false otherwise
	 */
	private function rowContainsBorderSymbol(int $_y): Bool
	{
		if (isset($this->symbolRows[$_y]) && count($this->symbolRows[$_y]) > 0) return true;
		else return false;
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
		foreach ($this->symbolRows as $symbolRow)
		{
			if (isset($symbolRow[$_x])) return true;
		}

		return false;
	}

	/**
	 * Returns the first border that contains a specific coordinate.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return OutputBorderPart|null The first output border that contains the coordinate or null if no border contains the coordinate
	 */
	private function getBorderContainingCoordinate(Coordinate $_coordinate)
	{
		foreach ($this->borders as $border)
		{
			if ($border->containsCoordinateBetweenEdges($_coordinate)) return $border;
		}

		return null;
	}
}
