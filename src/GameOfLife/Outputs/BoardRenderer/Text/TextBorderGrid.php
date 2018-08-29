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
use GameOfLife\Board;

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
		// Add the rendered border parts
		foreach ($_renderedBorderParts as $renderedBorderPart)
		{
			$this->addRenderedBorderPart($renderedBorderPart);
		}

		$this->autoCompleteBorderSymbolGrid();

		return $this->borderSymbolGrid;
	}

	/**
	 * Adds a rendered border part to the border symbol grid at a specific position.
	 *
	 * @param RenderedBorderPart $_renderedBorder The rendered border part
	 */
	public function addRenderedBorderPart($_renderedBorder)
	{
		$rawRenderedBorderPart = $_renderedBorder->rawRenderedBorderPart();
		$borderSymbols = $rawRenderedBorderPart;

		foreach ($_renderedBorder->borderPartGridPositions() as $at)
		{
			if (! isset($this->borderSymbolGrid[$at->y()])) $this->borderSymbolGrid[$at->y()] = array();
			$this->borderSymbolGrid[$at->y()][$at->x()] = array_shift($borderSymbols);
		}
	}

	/**
	 * Fills the gaps in the border symbol grid that exist because of the horizontal border rows and vertical borders columns.
	 */
	private function autoCompleteBorderSymbolGrid()
	{
		// Auto complete the grid
		for ($y = $this->borderPositionsGrid->getLowestRowId(); $y <= $this->borderPositionsGrid->getHighestRowId(); $y++)
		{
			$isBorderRow = ($y % 2 == 0);

			for ($x = $this->borderPositionsGrid->getLowestColumnId(); $x <= $this->borderPositionsGrid->getHighestColumnId(); $x++)
			{
				if (! isset($this->borderSymbolGrid[$y][$x]))
				{
					$isBorderColumn = ($x % 2 == 0);

					$rowContainsBorderSymbol = ($this->borderPositionsGrid->getMaximumBorderHeightInRow($y) > 0);
					$columnContainsBorderSymbol = ($this->borderPositionsGrid->getMaximumBorderWidthInColumn($x) > 0);

					/*
					 * If the current row is a border row and the row already contains a border symbol and
					 * a) The column in question is not a border column or
					 * b) The column in question is a border column and that column contains a border symbol
					 */
					$isBorderRowGap = ($isBorderRow && $rowContainsBorderSymbol && (! $isBorderColumn || $columnContainsBorderSymbol));

					/*
					 * If the current column is a border column and the border column already contains a border symbol and
					 * a) The row in question is not a border row or
					 * b) The row in question is a border row and that row contains a border symbol
					 */
					$isBorderColumnGap = ($isBorderColumn && $columnContainsBorderSymbol && (! $isBorderRow || $rowContainsBorderSymbol));

					if ($isBorderRowGap || $isBorderColumnGap) $this->borderSymbolGrid[$y][$x] = " ";
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
