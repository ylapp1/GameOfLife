<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\SymbolDefinition;

use BoardRenderer\Text\Border\BorderPart\CollisionDirection;

/**
 * Defines one collision symbol for one border symbol position (start, center and end) of a text border part.
 *
 * There are 40320 possible combinations of collision directions, so only a few should be defined that are known to occur.
 * When the border part is rendered the border part checks whether it has a collision symbol definition for the specific collision
 */
class CollisionSymbolDefinition
{
	// Attributes

	/**
	 * The collision symbol
	 *
	 * @var Bool $collisionSymbol
	 */
	private $collisionSymbol;

	/**
	 * The collision direction for which the collision symbol can be used
	 *
	 * @var CollisionDirection $collisionDirection
	 */
	private $collisionDirection;

	private $isStartPosition;

	private $isCenterPosition;

	private $isEndPosition;


	// Magic Methods

	/**
	 * CollisionSymbolDefinition constructor.
	 *
	 * @param String $_collisionSymbol The collision symbol
	 * @param CollisionDirection $_collisionDirection
	 * @param String $_position The position inside the border ("start", "center", "end")
	 */
	public function __construct(String $_collisionSymbol, CollisionDirection $_collisionDirection, String $_position)
	{
		$this->collisionSymbol = $_collisionSymbol;
		$this->collisionDirection = $_collisionDirection;

		$this->isStartPosition = true;
		$this->isCenterPosition = true;
		$this->isEndPosition = true;

		switch ($_position)
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

	/**
	 * Returns the collision symbol.
	 *
	 * @return String The collision symbol
	 */
	public function collisionSymbol(): String
	{
		return $this->collisionSymbol;
	}

	/**
	 * Returns the collision direction for which this collision symbol may be used.
	 *
	 * @return CollisionDirection
	 */
	public function collisionDirection(): CollisionDirection
	{
		return $this->collisionDirection;
	}

	/**
	 * @return bool
	 */
	public function isStartPosition(): bool
	{
		return $this->isStartPosition;
	}

	/**
	 * @return bool
	 */
	public function isCenterPosition(): bool
	{
		return $this->isCenterPosition;
	}

	/**
	 * @return bool
	 */
	public function isEndPosition(): bool
	{
		return $this->isEndPosition;
	}
}
