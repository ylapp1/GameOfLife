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

/**
 * Border grid for the TextBoardRenderer classes.
 */
class TextBorderGrid extends BaseBorderGrid
{
	private $borderSymbolGrid;

	// Class Methods

	/**
	 * Creates and returns the rendered border grid.
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels
	 *
	 * @return String[][] The rendered border grid
	 */
	public function renderBorderGrid(int $_fieldSize)
	{
		$this->renderBorderParts($_fieldSize);

		// Add the rendered border parts
		foreach ($this->renderedBorderParts as $renderedBorderPart)
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
		$borderSymbols = $_renderedBorder->rawRenderedBorderPart()->borderSymbols();

		// TODO: This won't work
		foreach ($_renderedBorder->borderPartGridPositions() as $symbolId => $borderPartGridPosition)
		{
			$xPosition = $borderPartGridPosition->x() * 2;
			$yPosition = $borderPartGridPosition->y() * 2;

			if (! isset($this->borderSymbolGrid[$yPosition])) $this->borderSymbolGrid[$yPosition] = array();
			$this->borderSymbolGrid[$yPosition][$xPosition] = $borderSymbols[$symbolId];
		}
	}

	/**
	 * Fills the gaps in the border symbol grid that exist because of vertical borders.
	 */
	private function autoCompleteBorderSymbolGrid()
	{
		// Auto complete the grid
		for ($y = $this->getLowestRowId(); $y <= $this->getHighestRowId(); $y++)
		{
			$isBorderRow = ($y % 2 == 0);

			for ($x = $this->getLowestColumnId(); $x <= $this->getHighestColumnId(); $x++)
			{
				if (!isset($this->borderSymbolGrid[$y][$x]))
				{
					$isBorderColumn = ($x % 2 == 0);

					if ($isBorderRow && $this->rowContainsBorderSymbol($y) ||
						$isBorderColumn && $this->columnContainsBorderSymbol($x))
					{
						$this->borderSymbolGrid[$y][$x] = " ";
					}
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