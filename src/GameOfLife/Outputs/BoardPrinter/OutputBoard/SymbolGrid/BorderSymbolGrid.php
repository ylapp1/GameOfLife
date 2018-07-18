<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\SymbolGrid;

use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBorder\OutputBorderPart;

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
	public function addBorder(OutputBorderPart $_border)
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
	 */
	public function drawBorders()
	{
		// Add the border symbols to the symbol grid
		foreach ($this->borders as $border)
		{
			$border->addBorderSymbolsToBorderSymbolGrid($this);
		}

		// Fill the border gaps with border symbols or empty space
		$boardWidth = 0; // TODO: Get the board width and fetch lowest/highest column id
		foreach ($this->symbolRows as $y => $symbolRow)
		{
			$rowContainsBorderSymbol = $this->rowContainsBorderSymbol($y);
			if (! $rowContainsBorderSymbol) continue;

			for ($x = 0; $x < $boardWidth; $x++)
			{
				if (! isset($this->symbolRows[$y][$x]))
				{
					$gapContainingBorder = null;
					if ($rowContainsBorderSymbol) $gapContainingBorder = $this->isGapInsideHorizontalBorder(new Coordinate($x, $y));
					elseif ($this->columnContainsBorderSymbol($x)) $gapContainingBorder = $this->isGapInsideVerticalBorder(new Coordinate($x, $y));

					if ($gapContainingBorder !== null) $symbol = $gapContainingBorder->symbolCenter; // TODO: Get center symbol from output border
					else $symbol = " ";

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
	 * Checks whether a gap is in the center of a horizontal border.
	 *
	 * @param Coordinate $_gapPosition The position of the gap in the symbol grid
	 *
	 * @return OutputBorderPart|null The first output border that contains the gap or null
	 */
	private function isGapInsideHorizontalBorder(Coordinate $_gapPosition)
	{
		foreach ($this->borders as $border)
		{
			if ($border->startsAt()->x() < $_gapPosition->x() && $border->endsAt()->x() > $_gapPosition->x())
			{
				return $border;
			}
		}

		return null;
	}

	/**
	 * Checks whether a gap is in the center of a vertical border.
	 *
	 * @param Coordinate $_gapPosition The position of the gap in the symbol grid
	 *
	 * @return OutputBorderPart|null The first output border that contains the gap or null
	 */
	private function isGapInsideVerticalBorder(Coordinate $_gapPosition)
	{
		foreach ($this->borders as $border)
		{
			if ($border->startsAt()->y() < $_gapPosition->y() && $border->endsAt()->y() > $_gapPosition->y())
			{
				return $border;
			}
		}

		return null;
	}
}
