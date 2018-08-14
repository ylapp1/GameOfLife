<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border\BorderPart\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Collision\Border\BorderPart\CollisionBorderPart;
use Output\BoardRenderer\Collision\Border\BorderPart\Shape\CollisionBorderPartShape;

/**
 * Shape for vertical border parts.
 */
abstract class VerticalCollisionBorderPartShape extends CollisionBorderPartShape
{
	// Class Methods

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
	 * @param CollisionBorderPart $_borderPart The other border part
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
