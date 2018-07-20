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
		$lowestColumnIndex = 0;
		$highestColumnIndex = 0;

		foreach ($this->symbolRows as $symbolRow)
		{
			$columnIndexes = array_keys($symbolRow);
			$symbolRowLowestColumnIndex = array_shift($columnIndexes);
			$symbolRowHighestColumnIndex = array_pop($columnIndexes);

			if ($symbolRowLowestColumnIndex < $lowestColumnIndex) $lowestColumnIndex = $symbolRowLowestColumnIndex;
			if ($symbolRowHighestColumnIndex > $highestColumnIndex) $highestColumnIndex = $symbolRowHighestColumnIndex;
		}


		$rowIndexes = array_keys($this->symbolRows);
		sort($rowIndexes);
		$lowestRowIndex = array_shift($rowIndexes);
		$highestRowIndex = array_pop($rowIndexes);

		foreach ($this->symbolRows as $y => $symbolRow)
		{
			$rowContainsBorderSymbol = $this->rowContainsBorderSymbol($y);
			$isBorderRow = ($y % 2 == 0);

			for ($x = $lowestColumnIndex; $x <= $highestColumnIndex; $x++)
			{
				if (!isset($this->symbolRows[$y][$x]))
				{
					if ($rowContainsBorderSymbol && $isBorderRow || $this->columnContainsBorderSymbol($x, $lowestRowIndex, $highestRowIndex))
					{
						$symbol = " ";

						$gapContainingBorder = $this->getBorderContainingCoordinate(new Coordinate($x, $y));
						if ($gapContainingBorder !== null) $symbol = $gapContainingBorder->borderSymbolCenter();

						$this->symbolRows[$y][$x] = $symbol;
					}
				}
			}

			ksort($this->symbolRows[$y]);
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
	 * @param int $_lowestRowIndex The lowest row index which will be ignored because it contains the outer upper border
	 * @param int $_highestRowIndex The highest row index which will be ignored because it contains the outer bottom border
	 *
	 * @return Bool True if the column contains a border symbol, false otherwise
	 */
	private function columnContainsBorderSymbol(int $_x, int $_lowestRowIndex, int $_highestRowIndex): Bool
	{
		// TODO: Fix this, determine which stuff is outer border

		foreach ($this->symbolRows as $y => $symbolRow)
		{
			if (! ($y == $_lowestRowIndex || $y == $_highestRowIndex))
			{
				if (isset($symbolRow[$_x])) return true;
			}
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
