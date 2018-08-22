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

	protected $isStartPosition;

	/**
	 * Indicates whether this is a center position.
	 * This distinction is necessary for the border start and end symbols
	 *
	 * @var Bool $isCenterPosition
	 */
	protected $isCenterPosition;

	protected $isEndPosition;

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
	 * @param String $_collisionPosition
	 * @param CollisionDirection $_collisionDirection
	 */
	public function __construct(int $_x, int $_y, String $_collisionPosition, CollisionDirection $_collisionDirection)
	{
		parent::__construct($_x, $_y);

		$this->collisionDirection = $_collisionDirection;

		$this->isStartPosition = false;
		$this->isCenterPosition = false;
		$this->isEndPosition = false;

		switch ($_collisionPosition)
		{
			case "start":
				$this->isStartPosition = true;
				break;
			case "center":
				$this->isCenterPosition = true;
				break;
			case "end":
				$this->isEndPosition = true;
				break;
		}
	}


	// Getters and Setters

	public function isStartPosition(): Bool
	{
		return $this->isStartPosition;
	}

	public function isCenterPosition(): Bool
	{
		return $this->isCenterPosition;
	}

	public function isEndPosition(): Bool
	{
		return $this->isEndPosition;
	}

	public function collisionDirection(): CollisionDirection
	{
		return $this->collisionDirection;
	}
}
