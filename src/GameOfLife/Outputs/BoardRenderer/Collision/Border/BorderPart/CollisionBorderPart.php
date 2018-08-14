<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Collision\Border\BorderPart;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use Output\BoardRenderer\Collision\Border\BorderPart\Shape\CollisionBorderPartShape;

/**
 * Class for border parts that can collide with other border parts.
 */
class CollisionBorderPart extends BaseBorderPart
{
	// Attributes

	/**
	 * The shape
	 * This must be an instance of CollisionBorderPartShape
	 *
	 * @var CollisionBorderPartShape $shape
	 */
	protected $shape;

	/**
	 * The list of collisions with other borders
	 *
	 * @var BorderPartCollision[] $collisions
	 */
	protected $collisions;


	// Magic Methods

	public function __construct(BaseBorder $_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, CollisionBorderPartShape $_shape)
	{
		parent::__construct($_parentBorder, $_startsAt, $_endsAt, $_shape);
		$this->collisions = array();
	}


	// Class Methods

	/**
	 * Checks whether this border part collides with another border part and adds a border part collision to this border
	 * part if a collision was detected.
	 *
	 * @param CollisionBorderPart $_borderPart The other border part
	 */
	public function checkCollisionWith($_borderPart)
	{
		if ($_borderPart instanceof CollisionborderPart)
		{
			$collisionPosition = $this->shape->getCollisionPositionWith($_borderPart);
			if ($collisionPosition !== null)
			{
				// Check whether the other border part is a inner or outer border part
				if ($_borderPart->parentBorder() === $this->parentBorder() ||
					$this->parentBorder()->containsBorder($_borderPart->parentBorder()))
				{
					$isOuterBorderPart = false;
				}
				else $isOuterBorderPart = true;

				$this->collisions[] = new BorderPartCollision($collisionPosition, $_borderPart, $isOuterBorderPart);
			}
		}
	}

	/**
	 * Returns whether this border part contains a specific coordinate.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if this border part contains the coordinate, false otherwise
	 */
	public function containsCoordinate(Coordinate $_coordinate): Bool
	{
		return $this->shape->containsCoordinate($_coordinate);
	}
}