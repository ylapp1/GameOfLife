<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\OutputBorder;

use GameOfLife\Coordinate;

/**
 * Class HorizontalOutputBorder
 */
class HorizontalOutputBorder extends LineOutputBorder
{
	public function __construct(Coordinate $_startsAt, Coordinate $_endsAt, array $_borderSymbols)
	{
		parent::__construct($_startsAt, $_endsAt, $_borderSymbols);
	}

	public function collidesWith(OutputBorder $_border): int
	{
		if ($_border->startsAt()->y() == $this->startsAt->y())
		{
			if ($_border->startsAt()->x() >= $this->startsAt->x() &&
				$_border->startsAt()->x() < $this->startsAt->x() + count($this->borderSymbols))
			{
				return $this->startsAt->x() + count($this->borderSymbols) - $_border->startsAt()->x();
			}
		}

		return null;
	}
}
