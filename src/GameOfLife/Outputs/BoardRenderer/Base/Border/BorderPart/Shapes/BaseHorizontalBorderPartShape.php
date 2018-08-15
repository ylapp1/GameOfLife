<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use GameOfLife\Coordinate;

/**
 * Base class for horizontal border part shapes.
 */
abstract class BaseHorizontalBorderPartShape extends BaseBorderPartShape
{
	protected function getBorderPartGridPositions(): array
	{
		$borderPartGridPositions = array();

		$startY = $this->parentBorderPart->startsAt()->y();
		for ($x = $this->parentBorderPart->startsAt()->x() + 1; $x <= $this->parentBorderPart->endsAt()->x(); $x++)
		{
			$borderPartGridPositions[] = new Coordinate($x, $startY);
		}

		return $borderPartGridPositions;
	}

	/**
	 * Returns whether the parent border part contains a specific coordinate.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the parent border part contains the coordinate, false otherwise
	 */
	public function containsCoordinate(Coordinate $_coordinate): Bool
	{
		if ($_coordinate->y() == $this->parentBorderPart->startsAt()->y())
		{
			if ($_coordinate->x() >= $this->parentBorderPart->startsAt()->x() &&
				$_coordinate->x() <= $this->parentBorderPart->endsAt()->x())
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the position at which the parent border part collides with another border part or null if there is no collision.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return Coordinate|null The position at which the parent border part collides with the other border part or null if there is no collision
	 */
	public function getCollisionPositionWith($_borderPart)
	{
		$checkCoordinate = clone $this->parentBorderPart->startsAt();

		for ($x = $this->parentBorderPart->startsAt()->x(); $x <= $this->parentBorderPart->endsAt()->x(); $x++)
		{
			$checkCoordinate->setX($x);
			if ($_borderPart->containsCoordinate($checkCoordinate))
			{
				return $checkCoordinate;
			}
		}

		return null;
	}
}
