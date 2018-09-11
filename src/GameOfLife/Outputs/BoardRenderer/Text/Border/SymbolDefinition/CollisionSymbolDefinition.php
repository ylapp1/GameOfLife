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
 * When the border part is rendered the border part checks whether it has a collision symbol definition for the specific collision direction.
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
	 * The collision directions for which the collision symbol may be used
	 *
	 * @var CollisionDirection[] $collisionDirection
	 */
	private $collisionDirections;

	/**
	 * Defines whether the collision symbol may be used for the start position of the border part
	 *
	 * @var Bool $isStartPosition
	 */
	private $isStartPosition;

	/**
	 * Defines whether the collision symbol may be used for a center position of the border part
	 *
	 * @var Bool $isCenterPosition
	 */
	private $isCenterPosition;

	/**
	 * Defines whether the collision symbol may be used for the end position of the border part
	 *
	 * @var Bool $isEndPosition
	 */
	private $isEndPosition;


	// Magic Methods

	/**
	 * CollisionSymbolDefinition constructor.
	 *
	 * @param String $_collisionSymbol The collision symbol
	 * @param CollisionDirection[] $_collisionDirections The collision directions for which the collision symbol may be used
	 * @param array $_positions The positions inside the border for which the collision symbol may be used ("start", "center" and/or "end")
	 */
	public function __construct(String $_collisionSymbol, array $_collisionDirections, array $_positions)
	{
		$this->collisionSymbol = $_collisionSymbol;
		$this->collisionDirections = $_collisionDirections;

		$this->isStartPosition = false;
		$this->isCenterPosition = false;
		$this->isEndPosition = false;

		foreach ($_positions as $position)
		{
			switch ($position)
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
	 * @return CollisionDirection[] The collision directions
	 */
	public function collisionDirections(): array
	{
		return $this->collisionDirections;
	}

	/**
	 * Returns whether the collision symbol may be used for the start position of the border part.
	 *
	 * @return Bool True if the collision symbol may be used for the start position of the border part, false otherwise
	 */
	public function isStartPosition(): Bool
	{
		return $this->isStartPosition;
	}

	/**
	 * Returns whether the collision symbol may be used for a center position of the border part.
	 *
	 * @return Bool True if the collision symbol may be used for a center position of the border part, false otherwise
	 */
	public function isCenterPosition(): Bool
	{
		return $this->isCenterPosition;
	}

	/**
	 * Returns whether the collision symbol may be used for the end position of the border part.
	 *
	 * @return Bool True if the collision symbol may be used for the end position of the border part, false otherwise
	 */
	public function isEndPosition(): Bool
	{
		return $this->isEndPosition;
	}
}
