<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPart;
use GameOfLife\Coordinate;

/**
 * Base class for vertical border part shapes.
 */
abstract class BaseVerticalBorderPartShape extends BaseBorderPartShape
{
	/**
	 * Returns the border part grid positions for the parent border part.
	 *
	 * @return Coordinate[] The border part grid positions
	 */
	protected function getBorderPartGridPositions(): array
	{
		$borderPartGridPositions = array();

		$startX = $this->parentBorderPart->startsAt()->x();
		for ($y = $this->parentBorderPart->startsAt()->y() + 1; $y <= $this->parentBorderPart->endsAt()->y(); $y++)
		{
			$borderPartGridPositions[] = new Coordinate($startX, $y);
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
		if ($_coordinate->x() == $this->parentBorderPart->startsAt()->x())
		{
			if ($_coordinate->y() >= $this->parentBorderPart->startsAt()->y() &&
				$_coordinate->y() <= $this->parentBorderPart->endsAt()->y())
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the position at which the parent border part collides with another border part or null if there is no collision.
	 *
	 * @param BorderPart $_borderPart The other border part
	 *
	 * @return Coordinate|null The position at which the parent border part collides with the other border part or null if there is no collision
	 */
	public function getCollisionPositionWith($_borderPart)
	{
		$checkCoordinate = clone $this->parentBorderPart->startsAt();

		for ($y = $this->parentBorderPart->startsAt()->y(); $y <= $this->parentBorderPart->endsAt()->y(); $y++)
		{
			$checkCoordinate->setY($y);
			if ($_borderPart->containsCoordinate($checkCoordinate))
			{
				return $checkCoordinate;
			}
		}

		return null;
	}
}