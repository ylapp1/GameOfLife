<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;


use GameOfLife\Coordinate;

abstract class LineOutputBorder extends OutputBorder
{
	public function addBorderSymbolsToBorderSymbolGrid(SymbolGrid $_borderSymbolGrid)
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
