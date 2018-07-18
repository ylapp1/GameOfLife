<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\OutputBorderPart;

use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\SymbolGrid\BorderSymbolGrid;

/**
 * Parent class for straight line (horizontal or vertical) border parts.
 */
abstract class LineOutputBorderPart extends OutputBorderPart
{
	// Class Methods

	/**
	 * Adds the border symbols of this border to a border symbol grid.
	 *
	 * @param BorderSymbolGrid $_borderSymbolGrid The border symbol grid
	 */
	public function addBorderSymbolsToBorderSymbolGrid(BorderSymbolGrid $_borderSymbolGrid)
	{
		$borderSymbolIndex = 0;

		for ($y = $this->startsAt->y(); $y <= $this->endsAt->y(); $y++)
		{
			for ($x = $this->startsAt->x(); $x <= $this->endsAt->x(); $x++)
			{
				$_borderSymbolGrid->setSymbolAt(new Coordinate($x, $y), $this->borderSymbols[$borderSymbolIndex]);
				$borderSymbolIndex++;
			}
		}
	}
}