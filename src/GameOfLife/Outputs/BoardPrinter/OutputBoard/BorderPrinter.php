<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;


use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\HorizontalOutputBorderPart;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\OutputBorderPart;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\VerticalOutputBorderPart;

/**
 * Prints borders to a symbol grid.
 */
class BorderPrinter
{
	/**
	 * The list of borders
	 *
	 * @var OutputBorderPart[] $borders
	 */
	private $borders;


	public function __construct()
	{
		$this->borders = array();
	}

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
	 * Adds the border symbols to a symbol grid.
	 * The grid has two rows per cell symbol row, one for the border above the row and the other for the borders inside the row.
	 * It also has two columns per cell column, one for the border left and the other for the cell itself.
	 *
	 * @param SymbolGrid $_symbolGrid The symbol grid
	 */
	public function drawBorders(SymbolGrid $_symbolGrid)
	{
		// Add the border symbols to the symbol grid
		foreach ($this->borders as $border)
		{
			$border->addBorderSymbolsToBorderSymbolGrid($_symbolGrid);
		}

		// Fill the border gaps with border symbols or empty space
		$lowestColumnIndex = 0;
		$highestColumnIndex = 0;

		foreach ($_symbolGrid->symbolRows() as $symbolRow)
		{
			$columnIndexes = array_keys($symbolRow);
			$symbolRowLowestColumnIndex = array_shift($columnIndexes);
			$symbolRowHighestColumnIndex = array_pop($columnIndexes);

			if ($symbolRowLowestColumnIndex < $lowestColumnIndex) $lowestColumnIndex = $symbolRowLowestColumnIndex;
			if ($symbolRowHighestColumnIndex > $highestColumnIndex) $highestColumnIndex = $symbolRowHighestColumnIndex;
		}

		$symbolRowIndexes = array_keys($_symbolGrid->symbolRows());
		sort($symbolRowIndexes);
		$lowestSymbolRowIndex = array_shift($symbolRowIndexes);
		$highestSymbolRowIndex = array_pop($symbolRowIndexes);

		foreach ($_symbolGrid->symbolRows() as $y => $symbolRow)
		{
			$rowContainsBorderSymbol = $this->rowContainsBorderSymbol($_symbolGrid, $y);
			$isBorderRow = ($y % 2 == 0);

			for ($x = $lowestColumnIndex; $x <= $highestColumnIndex; $x++)
			{
				$columnContainsVerticalBorder = $this->columnContainsVerticalBorder($x);
				$isBorderColumn = ($x % 2 == 0);

				if (! isset($symbolRow[$x]) && (! $isBorderColumn || $columnContainsVerticalBorder))
				{
					if ($isBorderRow)
					{
						if (! $rowContainsBorderSymbol) continue;
						else
						{
							$borderCollidesWithColumn = false;
							foreach ($this->getHorizontalBordersOfBorderRow($y) as $border)
							{
								if (($border->startsAt()->x() + 1) * 2 <= $x || ($border->endsAt()->x() + 1) * 2 >= $x && $x == $highestColumnIndex)
								{
									$borderCollidesWithColumn = true;
								}
							}

							if ($borderCollidesWithColumn) continue;
						}
					}

					$gapContainingBorder = $this->getBorderContainingCoordinate(new Coordinate($x, $y));
					if ($gapContainingBorder) $borderSymbol = $gapContainingBorder->borderSymbolCenter();
					else $borderSymbol = " ";

					$_symbolGrid->symbolRows()[$y][$x] = $borderSymbol;
				}
			}

			ksort($_symbolGrid->symbolRows()[$y]);
		}
	}

	/**
	 * @param int $_y
	 *
	 * @return HorizontalOutputBorderPart[]
	 */
	private function getHorizontalBordersOfBorderRow(int $_y)
	{
		$borders = array();

		foreach ($this->borders as $border)
		{
			if ($border instanceof HorizontalOutputBorderPart)
			{
				if ($border->startsAt()->y() * 2 == $_y && $border->endsAt()->y() * 2 == $_y) $borders[] = $border;
			}
		}

		return $borders;
	}

	/**
	 * Returns whether a specific row contains any border symbols.
	 *
	 * @param SymbolGrid $_symbolGrid The symbol grid
	 * @param int $_y The Y-Position of the row
	 *
	 * @return Bool True if the row contains a border symbol, false otherwise
	 */
	private function rowContainsBorderSymbol(SymbolGrid $_symbolGrid, int $_y): Bool
	{
		if (isset($this->symbolRows[$_y]) && count($_symbolGrid->symbolRows()[$_y]) > 0) return true;
		else return false;
	}

	/**
	 * Returns whether a specific column contains any border symbols.
	 *
	 * @param int $_x The X-Position of the column
	 *
	 * @return Bool True if the column contains a border symbol, false otherwise
	 */
	private function columnContainsVerticalBorder(int $_x): Bool
	{
		// TODO: Fix this, determine which stuff is outer border
		foreach ($this->borders as $border)
		{
			if ($border instanceof VerticalOutputBorderPart)
			{
				if ($border->startsAt()->x() == $_x && $border->endsAt()->x() == $_x) return true;
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
