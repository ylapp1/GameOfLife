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
use BoardRenderer\Text\Border\BorderPart\TextRawRenderedBorderPart;

/**
 * Border grid for the TextBoardRenderer classes.
 */
class TextBorderGrid extends BaseBorderGrid
{
	private $borderSymbolGrid;

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
		/** @var TextRawRenderedBorderPart $rawRenderedBorderPart */
		$rawRenderedBorderPart = $_renderedBorder->rawRenderedBorderPart();
		$borderSymbols = $rawRenderedBorderPart->borderSymbols();
		$borderSymbolPositions = $rawRenderedBorderPart->borderSymbolPositions();

		foreach ($borderSymbolPositions as $symbolId => $at)
		{
			$gridX = $_renderedBorder->parentBorderPart()->startsAt()->x() * 2 + $at->x();
			$gridY = $_renderedBorder->parentBorderPart()->startsAt()->y() * 2 + $at->y();

			if (! isset($this->borderSymbolGrid[$gridY])) $this->borderSymbolGrid[$gridY] = array();
			$this->borderSymbolGrid[$gridY][$gridX] = $borderSymbols[$symbolId];
		}
	}

	/**
	 * Fills the gaps in the border symbol grid that exist because of vertical borders.
	 */
	private function autoCompleteBorderSymbolGrid()
	{
		// Auto complete the grid
		// TODO: Adjust border positions grid for text ...
		for ($y = $this->borderPositionsGrid->getLowestRowId(); $y <= $this->borderPositionsGrid->getHighestRowId() * 2; $y++)
		{
			$isBorderRow = ($y % 2 == 0);

			for ($x = $this->borderPositionsGrid->getLowestColumnId(); $x <= $this->borderPositionsGrid->getHighestColumnId() * 2; $x++)
			{
				if (! isset($this->borderSymbolGrid[$y][$x]))
				{
					$isBorderColumn = ($x % 2 == 0);

					/*
					 * If the current row is a border row and the row already contains a border symbol and
					 * a) The column in question is not a border column or
					 * b) The column in question is a border column and that column contains a border symbol
					 */
					$isBorderRowGap = ($isBorderRow && $this->rowContainsBorderSymbol($y) && (! $isBorderColumn || $this->columnContainsBorderSymbol($x)));

					/*
					 * If the current column is a border column and the border column already contains a border symbol and
					 * a) The row in question is not a border row or
					 * b) The row in question is a border row and that row contains a border symbol
					 */
					$isBorderColumnGap = ($isBorderColumn && $this->columnContainsBorderSymbol($x) && (! $isBorderRow || $this->rowContainsBorderSymbol($y)));

					if ($isBorderRowGap || $isBorderColumnGap) $this->borderSymbolGrid[$y][$x] = " ";
				}
			}
		}

		// Sort the rows by array keys
		foreach ($this->borderSymbolGrid as $borderSymbolRow)
		{
			ksort($borderSymbolRow);
		}
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
		foreach ($this->borderSymbolGrid as $y => $borderSymbolRow)
		{
			if (isset($borderSymbolRow[$_x])) return true;
		}

		return false;
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
		if (isset($this->borderSymbolGrid[$_y])) return true;
		else return false;
	}
}