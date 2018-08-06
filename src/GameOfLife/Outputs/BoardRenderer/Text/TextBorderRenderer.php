<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;

use Output\BoardRenderer\Base\BaseBorderRenderer;
use Output\BoardRenderer\Base\Border\BaseBorder;

/**
 * Renders a border and its inner borders and adds them to a canvas.
 */
class TextBorderRenderer extends BaseBorderRenderer
{
	/**
	 * Renders a border and adds it to a canvas.
	 *
	 * @param BaseBorder $_border The border
	 * @param TextCanvas $_canvas The canvas
	 */
	public function renderBorder($_border, $_canvas)
	{
		parent::renderBorder($_border, $_canvas);
		$this->autoCompleteBorderSymbolGrid($_canvas->borderSymbolGrid());
	}

	/**
	 * Fills the gaps in the border symbol grid that exist because of vertical borders.
	 *
	 * @param String[][] $_borderSymbolGrid The border symbol grid
	 */
	private function autoCompleteBorderSymbolGrid(array $_borderSymbolGrid)
	{
		// Find the lowest and highest column ids
		$columnIds = array();
		foreach ($this->borderSymbolGrid as $borderSymbolRow)
		{
			$columnIds = array_merge($columnIds, array_keys($borderSymbolRow));
		}
		natsort($columnIds);
		$lowestColumnId = array_shift($columnIds);
		$highestColumnId = array_pop($columnIds);


		// TODO: Border renderer must render the complete border grid and add it to the text canvas at once because only this class can know whether a coordinate is inside a border
		// TODO: (Diagonal, Curved, etc)

		for ($x = $lowestColumnId; $x <= $highestColumnId; $x++)
		{
			// TODO: Fix this, determine which stuff is outer border
			$columnContainsBorderSymbol = $this->columnContainsBorderSymbol($_borderSymbolGrid, $x);
			$isBorderColumn = ($x % 2 == 0);

			foreach ($this->borderSymbolGrid as $y => $borderSymbolRow)
			{
				$isBorderRow = ($y % 2 == 0);

				if ($isBorderRow)
				{
					if (! $isBorderColumn) $borderSymbol = " ";
					elseif ($columnContainsBorderSymbol)
					{
						// TODO: Case A: border column is inside border
						// TODO: Case B: border column is outside border
					}
					else continue;
				}
				else
				{
					if ($isBorderColumn && $columnContainsBorderSymbol)
					{
						// TODO: Case A: border column is inside border
						// TODO: Case B: border column is outside border
					}
					if (! $isBorderColumn || $columnContainsBorderSymbol)
					{
						$this->borderSymbolGrid[$y][$x] = " ";
					}
				}

				// TODO: Auto complete borders
			}

			ksort($this->borderSymbolGrid[$y]);
		}
	}

	/**
	 * Returns whether a specific column contains any border symbols.
	 *
	 * @param String[][] $_borderSymbolGrid The border symbol grid
	 * @param int $_x The X-Position of the column
	 *
	 * @return Bool True if the column contains a border symbol, false otherwise
	 */
	private function columnContainsBorderSymbol(array $_borderSymbolGrid, int $_x): Bool
	{
		foreach ($_borderSymbolGrid as $y => $borderSymbolRow)
		{
			if ($borderSymbolRow[$_x]) return true;
		}

		return false;
	}
}
