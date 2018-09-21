<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart;

use Utils\Geometry\Coordinate;

/**
 * Stores additional necessary information for text border part collision positions.
 */
class TextBorderPartCollisionPosition extends Coordinate
{
	// Attributes

	/**
	 * The direction from which the other border part collides
	 *
	 * @var CollisionDirection $collisionDirection
	 */
	protected $collisionDirection;


	// Magic Methods

	/**
	 * TextBorderPartCollisionPosition constructor.
	 *
	 * @param Coordinate $_at The position of the collision in the board field grid
	 * @param CollisionDirection $_collisionDirection The direction from which the other border part collides
	 */
	public function __construct(Coordinate $_at, CollisionDirection $_collisionDirection)
	{
		parent::__construct($_at->x(), $_at->y());
		$this->collisionDirection = $_collisionDirection;
	}


	// Getters and Setters

	/**
	 * Returns the collision direction.
	 *
	 * @return CollisionDirection The collision direction
	 */
	public function collisionDirection(): CollisionDirection
	{
		return $this->collisionDirection;
	}
}
