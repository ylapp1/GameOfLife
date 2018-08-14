<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Collision\Border\BorderPart\Shape;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use Output\BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;

/**
 * Base class for border part shapes that can collide with other border part shapes.
 */
abstract class CollisionBorderPartShape extends BaseBorderPartShape
{
	// Class Methods

	/**
	 * Returns whether the parent border part contains a specific coordinate.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the parent border part contains the coordinate, false otherwise
	 */
	abstract public function containsCoordinate(Coordinate $_coordinate): Bool;

	/**
	 * Returns the position at which the parent border part collides with another border part or null if there is no collision.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return Coordinate|null The position at which the parent border part collides with the other border part or null if there is no collision
	 */
	abstract public function getCollisionPositionWith($_borderPart);
}
