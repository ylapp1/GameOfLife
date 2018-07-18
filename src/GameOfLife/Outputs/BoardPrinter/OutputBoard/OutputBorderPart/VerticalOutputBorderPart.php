<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\OutputBorderPart;

use GameOfLife\Coordinate;

/**
 * Class VerticalOutputBorderPart
 */
class VerticalOutputBorderPart extends LineOutputBorderPart
{
	// Magic Methods

	/**
	 * VerticalOutputBorderPart constructor.
	 *
	 * @param Coordinate $_startsAt The start coordinate of this border
	 * @param Coordinate $_endsAt The end coordinate of this border
	 * @param String $_borderSymbolStart The symbol for the start of the border
	 * @param String $_borderSymbolCenter The symbol for the center parts of the border
	 * @param String $_borderSymbolEnd The symbol for the end of the border
	 * @param String $_borderSymbolOuterBorderCollisionStart The symbol for the start of the border when the start collides with an outer border
	 * @param String $_borderSymbolOuterBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an outer border
	 * @param String $_borderSymbolOuterBorderCollisionEnd The symbol for the end of the border when the end collides with an outer border
	 * @param String $_borderSymbolInnerBorderCollisionStart The symbol for the start of the border when the start collides with an inner border
	 * @param String $_borderSymbolInnerBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an inner border
	 * @param String $_borderSymbolInnerBorderCollisionEnd The symbol for the end of the border when the end collides with an inner border
	 */
	public function __construct(Coordinate $_startsAt, Coordinate $_endsAt, String $_borderSymbolStart, String $_borderSymbolCenter, String $_borderSymbolEnd, String $_borderSymbolOuterBorderCollisionStart, String $_borderSymbolOuterBorderCollisionCenter, String $_borderSymbolOuterBorderCollisionEnd, String $_borderSymbolInnerBorderCollisionStart, String $_borderSymbolInnerBorderCollisionCenter, String $_borderSymbolInnerBorderCollisionEnd)
	{
		parent::__construct($_startsAt, $_endsAt, $_borderSymbolStart, $_borderSymbolCenter, $_borderSymbolEnd, $_borderSymbolOuterBorderCollisionStart, $_borderSymbolOuterBorderCollisionCenter, $_borderSymbolOuterBorderCollisionEnd, $_borderSymbolInnerBorderCollisionStart, $_borderSymbolInnerBorderCollisionCenter, $_borderSymbolInnerBorderCollisionEnd);
	}


	// Class Methods

	/**
	 * Returns the position where a border collides with this border.
	 *
	 * @param OutputBorderPart $_border The border
	 *
	 * @return int|null The position in this border at which the other border collides with this border or null if the borders don't collide
	 */
	public function collidesWith(OutputBorderPart $_border): int
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

	/**
	 * Calculates and returns the length of this border without start/end symbols.
	 *
	 * @return int The length of this border without start/end symbols
	 */
	protected function getBorderLength(): int
	{
		return $this->endsAt->y() - $this->startsAt->y() + 1;
	}
}
