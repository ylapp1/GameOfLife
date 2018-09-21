<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart;

/**
 * Defines the direction(s) from which a border part collides with a single fraction of another border part.
 */
class CollisionDirection
{
	// Attributes

	// Vertical collisions

	/**
	 * Indicates whether this is a collision from the top of the border symbol position
	 *
	 * @var Bool $isCollisionFromTop
	 */
	protected $isCollisionFromTop;

	/**
	 * Indicates whether this is a collision from the bottom of the border symbol position
	 *
	 * @var Bool $isCollisionFromBottom
	 */
	protected $isCollisionFromBottom;


	// Horizontal collisions

	/**
	 * Indicates whether this is a collision from the left of the border symbol position
	 *
	 * @var Bool $isCollisionFromLeft
	 */
	protected $isCollisionFromLeft;

	/**
	 * Indicates whether this is a collision from the right of the border symbol position
	 *
	 * @var Bool $isCollisionFromRight
	 */
	protected $isCollisionFromRight;


	// Diagonal collisions

	/**
	 * Indicates whether this is a collision from the top left of the border symbol position
	 *
	 * @var Bool $isCollisionFromTopLeft
	 */
	private $isCollisionFromTopLeft;

	/**
	 * Indicates whether this is a collision from the top right of the border symbol position
	 *
	 * @var Bool $isCollisionFromTopRight
	 */
	private $isCollisionFromTopRight;

	/**
	 * Indicates whether this is a collision from the bottom left of the border symbol position
	 *
	 * @var Bool $isCollisionFromBottomLeft
	 */
	private $isCollisionFromBottomLeft;

	/**
	 * Indicates whether this is a collision from the bottom right of the border symbol position
	 *
	 * @var $isCollisionFromBottomRight
	 */
	private $isCollisionFromBottomRight;


	// Magic Methods

	/**
	 * CollisionSymbolDefinition constructor.
	 *
	 * @param String[] $_collisionDirections The collision directions as words ("top", "left", "right", "bottom", "top-left", "top-right", "bottom-left", "bottom-right")
	 */
	public function __construct(array $_collisionDirections)
	{
		$this->isCollisionFromTop = false;
		$this->isCollisionFromBottom = false;
		$this->isCollisionFromLeft = false;
		$this->isCollisionFromRight = false;
		$this->isCollisionFromTopLeft = false;
		$this->isCollisionFromTopRight = false;
		$this->isCollisionFromBottomLeft = false;
		$this->isCollisionFromBottomRight = false;

		foreach ($_collisionDirections as $collisionDirection)
		{
			$this->parseCollisionDirection($collisionDirection);
		}
	}

	/**
	 * Returns whether another collision direction equals this collision direction.
	 *
	 * @param CollisionDirection $_collisionDirection The other collision direction
	 *
	 * @return Bool True if the other collision direction equals this collision direction, false otherwise
	 */
	public function equals(CollisionDirection $_collisionDirection): Bool
	{
		if ($this->isCollisionFromTop == $_collisionDirection->isCollisionFromTop() &&
		    $this->isCollisionFromBottom == $_collisionDirection->isCollisionFromBottom() &&
		    $this->isCollisionFromLeft == $_collisionDirection->isCollisionFromLeft() &&
		    $this->isCollisionFromRight == $_collisionDirection->isCollisionFromRight() &&
		    $this->isCollisionFromTopLeft == $_collisionDirection->isCollisionFromTopLeft() &&
		    $this->isCollisionFromTopRight == $_collisionDirection->isCollisionFromTopRight() &&
		    $this->isCollisionFromBottomLeft == $_collisionDirection->isCollisionFromBottomLeft() &&
		    $this->isCollisionFromBottomRight == $_collisionDirection->isCollisionFromBottomRight())
		{
			return true;
		}
		else return false;
	}


	// Getters and setters

	/**
	 * Returns whether this is a collision from the top of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the top of the border symbol position, false otherwise
	 */
	public function isCollisionFromTop(): Bool
	{
		return $this->isCollisionFromTop;
	}

	/**
	 * Returns whether this is a collision from the bottom of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the bottom of the border symbol position, false otherwise
	 */
	public function isCollisionFromBottom(): Bool
	{
		return $this->isCollisionFromBottom;
	}

	/**
	 * Returns whether this is a collision from the left of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the left of the border symbol position, false otherwise
	 */
	public function isCollisionFromLeft(): Bool
	{
		return $this->isCollisionFromLeft;
	}

	/**
	 * Returns whether this is a collision from the right of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the right of the border symbol position, false otherwise
	 */
	public function isCollisionFromRight(): Bool
	{
		return $this->isCollisionFromRight;
	}

	/**
	 * Returns whether this is a collision from the top left of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the top left of the border symbol position, false otherwise
	 */
	public function isCollisionFromTopLeft(): Bool
	{
		return $this->isCollisionFromTopLeft;
	}

	/**
	 * Returns whether this is a collision from the top right of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the top right of the border symbol position, false otherwise
	 */
	public function isCollisionFromTopRight(): Bool
	{
		return $this->isCollisionFromTopRight;
	}

	/**
	 * Returns whether this is a collision from the bottom left of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the bottom left of the border symbol position, false otherwise
	 */
	public function isCollisionFromBottomLeft(): Bool
	{
		return $this->isCollisionFromBottomLeft;
	}

	/**
	 * Returns whether this is a collision from the bottom right of the border symbol position.
	 *
	 * @return Bool True if this is a collision from the bottom right of the border symbol position, false otherwise
	 */
	public function isCollisionFromBottomRight(): Bool
	{
		return $this->isCollisionFromBottomRight;
	}


	// Class Methods

	/**
	 * Parses a collision direction string.
	 *
	 * @param String $_collisionDirection The collision direction string
	 */
	private function parseCollisionDirection(String $_collisionDirection)
	{
		switch ($_collisionDirection)
		{
			case "top":
				$this->isCollisionFromTop = true;
				break;
			case "bottom":
				$this->isCollisionFromBottom = true;
				break;
			case "left":
				$this->isCollisionFromLeft = true;
				break;
			case "right":
				$this->isCollisionFromRight = true;
				break;
			case "top-left":
				$this->isCollisionFromTopLeft = true;
				break;
			case "top-right":
				$this->isCollisionFromTopRight = true;
				break;
			case "bottom-left":
				$this->isCollisionFromBottomLeft = true;
				break;
			case "bottom-right":
				$this->isCollisionFromBottomRight = true;
				break;
		}
	}
}
