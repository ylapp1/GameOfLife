<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\BaseBorderRenderer;
use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Text\Border\BorderPart\TextBorderPart;

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
		$this->autoCompleteBorderSymbolGrid($_canvas->borderSymbolGrid(), $_border);
	}

	/**
	 * Fills the gaps in the border symbol grid that exist because of vertical borders.
	 *
	 * @param String[][] $_borderSymbolGrid The border symbol grid
	 * @param BaseBorder $_border The border
	 */
	private function autoCompleteBorderSymbolGrid(array $_borderSymbolGrid, $_border)
	{
		// Find the lowest and highest column ids
		$columnIds = array();
		foreach ($_borderSymbolGrid as $borderSymbolRow)
		{
			$columnIds = array_merge($columnIds, array_keys($borderSymbolRow));
		}
		natsort($columnIds);
		$lowestColumnId = array_shift($columnIds);
		$highestColumnId = array_pop($columnIds);

		for ($x = $lowestColumnId; $x <= $highestColumnId; $x++)
		{
			// TODO: Fix this, determine which stuff is outer border
			$columnContainsBorderSymbol = $this->columnContainsBorderSymbol($_borderSymbolGrid, $x);
			$isBorderColumn = ($x % 2 == 0);

			if ($isBorderColumn && ! $columnContainsBorderSymbol) continue;


			$borderSymbol = " ";

			if ($isBorderColumn) $borderCollisionCheckCoordinateX = $x / 2;
			else $borderCollisionCheckCoordinateX = ($x - 1) / 2;

			foreach ($_borderSymbolGrid as $y => $borderSymbolRow)
			{
				$isBorderRow = ($y % 2 == 0);

				if ($isBorderRow)
				{
					$borderCollisionCheckCoordinate = new Coordinate($borderCollisionCheckCoordinateX, $y);

					/** @var TextBorderPart $borderPart */
					foreach ($_border->getBorderParts() as $borderPart)
					{
						if ($borderPart->containsCoordinateBetweenEdges($borderCollisionCheckCoordinate))
						{
							$borderSymbol = $borderPart->borderSymbolCenter();
							break;
						}
					}


					$_borderSymbolGrid[$y][$x] = $borderSymbol;
				}

				ksort($_borderSymbolGrid[$y]);
			}
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
			if (isset($borderSymbolRow[$_x])) return true;
		}

		return false;
	}
}
