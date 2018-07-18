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
 * Class VerticalOutputBorder
 */
class VerticalOutputBorder extends LineOutputBorder
{
	public function __construct(Coordinate $_startsAt, Coordinate $_endsAt, array $_borderSymbols)
	{
		parent::__construct($_startsAt, $_endsAt, $_borderSymbols);
	}

	/**
	 * Returns the position where a border collides with this border.
	 *
	 * @param OutputBorder $_border The border
	 *
	 * @return int|null The position in this border at which the other border collides with this border or null if the borders don't collide
	 */
	public function collidesWith(OutputBorder $_border): int
	{
		if ($_border->startsAt()->x() == $this->startsAt->x())
		{
			if ($_border->startsAt()->y() >= $this->startsAt->y() &&
				$_border->startsAt()->y() < $this->startsAt->y() + count($this->borderSymbols))
			{
				return $this->startsAt->y() + count($this->borderSymbols) - $_border->startsAt()->y();
			}
		}

		return null;
	}
}
