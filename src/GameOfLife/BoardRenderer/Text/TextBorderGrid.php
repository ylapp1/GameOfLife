<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use BoardRenderer\Base\BaseBorderGrid;
use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use Simulator\Board;
use Utils\Geometry\Coordinate;

/**
 * Border grid for the TextBoardRenderer classes.
 */
class TextBorderGrid extends BaseBorderGrid
{
	// Attributes

	/**
	 * Stores the list of border symbols
	 *
	 * @var String[][] The list of border symbols
	 */
	private $borderSymbolGrid;


	// Magic Methods

	/**
	 * TextBorderGrid constructor.
	 *
	 * @param Board $_board The board for which this text border grid will be used
	 */
	public function __construct(Board $_board)
	{
		parent::__construct(new TextBorderPositionsGrid($_board));
		$this->borderSymbolGrid = array();
	}

	// Class Methods

	/**
	 * Creates and returns a rendered border grid from a list of rendered border parts.
	 *
	 * @param RenderedBorderPart[] $_renderedBorderParts The list of rendered border parts
	 * @param int $_fieldSize The height/width of a field in symbols
	 *
	 * @return String[][] The rendered border grid
	 */
	public function renderTotalBorderGrid(array $_renderedBorderParts, int $_fieldSize)
	{
		foreach ($_renderedBorderParts as $renderedBorderPart)
		{
			$this->addRenderedBorderPart($renderedBorderPart);
		}

		$this->autoCompleteBorderSymbolGrid();

		return $this->borderSymbolGrid;
	}

	/**
	 * Adds a rendered border part to the border symbol grid.
	 *
	 * @param RenderedBorderPart $_renderedBorderPart The rendered border part
	 */
	public function addRenderedBorderPart(RenderedBorderPart $_renderedBorderPart)
	{
		$borderSymbols = $_renderedBorderPart->rawRenderedBorderPart();

		foreach ($_renderedBorderPart->borderPartGridPositions() as $at)
		{
			// Initialize the border row
			if (! isset($this->borderSymbolGrid[$at->y()])) $this->borderSymbolGrid[$at->y()] = array();

			// Add the border symbol
			$this->borderSymbolGrid[$at->y()][$at->x()] = array_shift($borderSymbols);
		}
	}

	/**
	 * Fills the gaps in the border symbol grid that exist because of the horizontal border rows and vertical borders columns.
	 */
	private function autoCompleteBorderSymbolGrid()
	{
		$lowestColumnId = $this->borderPositionsGrid->getLowestColumnId();
		$highestColumnId = $this->borderPositionsGrid->getHighestColumnId();

		// Auto complete the grid
		for ($y = $this->borderPositionsGrid->getLowestRowId(); $y <= $this->borderPositionsGrid->getHighestRowId(); $y++)
		{
			$isBorderRow = ($y % 2 == 0);
			if (! $isBorderRow || isset($this->borderSymbolGrid[$y]))
			{
				for ($x = $lowestColumnId; $x <= $highestColumnId; $x++)
				{
					if (! isset($this->borderSymbolGrid[$y][$x]))
					{
						$isBorderColumn = ($x % 2 == 0);

						$rowContainsBorderSymbol = ($this->borderPositionsGrid->getMaximumBorderHeightInRow($y) > 0);
						$columnContainsBorderSymbol = ($this->borderPositionsGrid->getMaximumBorderWidthInColumn($x) > 0);

						$isBoardFieldRow = $this->borderPositionsGrid->isBoardFieldRow($y);
						$isBoardFieldColumn = $this->borderPositionsGrid->isBoardFieldColumn($x);

						/*
						 * If the current row is a border row and the row already contains a border symbol and
						 * a) The column in question is not a border column or
						 * b) The column in question is a border column and that column contains a border symbol
						 */
						$isBorderRowGap = ($isBorderRow && $rowContainsBorderSymbol && ($isBoardFieldColumn || ($isBorderColumn && $columnContainsBorderSymbol)));

						/*
						 * If the current column is a border column and the border column already contains a border symbol and
						 * a) The row in question is not a border row or
						 * b) The row in question is a border row and that row contains a border symbol
						 */
						$isBorderColumnGap = ($isBorderColumn && $columnContainsBorderSymbol && ($isBoardFieldRow || ($isBorderRow && $rowContainsBorderSymbol)));

						if ($isBorderRowGap || $isBorderColumnGap)
						{
							// Find out whether a border part contains the gap
							$borderPartContainingGap = null;
							if ($isBorderRowGap && $isBorderColumnGap)
							{ // The gap is in a border row and a border column, check whether it is inside a border
								$gapCoordinate = new Coordinate($x, $y);

								/** @var TextBorderPart $borderPart */
								foreach ($this->borderParts as $borderPart)
								{
									if ($borderPart->containsCoordinate($gapCoordinate))
									{
										$borderPartContainingGap = $borderPart;
										break;
									}
								}
							}

							if ($borderPartContainingGap) $gapSymbol = $borderPartContainingGap->borderSymbolDefinition()->centerSymbol();
							else $gapSymbol = " ";

							$this->borderSymbolGrid[$y][$x] = $gapSymbol;
						}
					}
				}
			}
		}

		// Sort the rows by array keys
		foreach ($this->borderSymbolGrid as $y => $borderSymbolRow)
		{
			ksort($this->borderSymbolGrid[$y]);
		}
	}
}
