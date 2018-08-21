<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart;

use GameOfLife\Coordinate;

/**
 * Stores additional necessary information for text border part collision positions.
 */
class TextBorderPartCollisionPosition extends Coordinate
{
	// Attributes

	/**
	 * Indicates whether this is a center position.
	 * This distinction is necessary for the border start and end symbols
	 *
	 * @var Bool $isCenterPosition
	 */
	protected $isCenterPosition;

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
	 * @param int $_x
	 * @param int $_y
	 * @param bool $_isCenterPosition
	 * @param CollisionDirection $_collisionDirection
	 */
	public function __construct(int $_x, int $_y, Bool $_isCenterPosition = false, CollisionDirection $_collisionDirection)
	{
		parent::__construct($_x, $_y);

		$this->isCenterPosition = $_isCenterPosition;
		$this->collisionDirection = $_collisionDirection;
	}


	// Getters and Setters

	public function isCenterPosition(): Bool
	{
		return $this->isCenterPosition;
	}

	public function collisionDirection(): CollisionDirection
	{
		return $this->collisionDirection;
	}
}
