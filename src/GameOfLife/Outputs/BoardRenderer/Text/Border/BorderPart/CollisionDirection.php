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

	// vertical collisions

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


	// horizontal collisions

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


	// diagonal collisions

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



	// Getters and setters

	/**
	 * Returns whether this collision symbol is for a collision from the top.
	 *
	 * @return bool
	 */
	public function isCollisionFromTop(): bool
	{
		return $this->isCollisionFromTop;
	}

	/**
	 * @return bool
	 */
	public function isCollisionFromBottom(): bool
	{
		return $this->isCollisionFromBottom;
	}

	/**
	 * @return bool
	 */
	public function isCollisionFromLeft(): bool
	{
		return $this->isCollisionFromLeft;
	}

	/**
	 * @return bool
	 */
	public function isCollisionFromRight(): bool
	{
		return $this->isCollisionFromRight;
	}

	/**
	 * @return bool
	 */
	public function isCollisionFromTopLeft(): bool
	{
		return $this->isCollisionFromTopLeft;
	}

	/**
	 * @return bool
	 */
	public function isCollisionFromTopRight(): bool
	{
		return $this->isCollisionFromTopRight;
	}

	/**
	 * @return bool
	 */
	public function isCollisionFromBottomLeft(): bool
	{
		return $this->isCollisionFromBottomLeft;
	}

	/**
	 * @return bool
	 */
	public function isCollisionFromBottomRight(): bool
	{
		return $this->isCollisionFromBottomRight;
	}
}
