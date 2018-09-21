<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\SymbolDefinition;

use BoardRenderer\Text\Border\BorderPart\CollisionDirection;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextBorderPartCollisionPosition;

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

	/**
	 * Defines whether the collision position must be inside a border row
	 *
	 * @var Bool $isInBorderRow
	 */
	private $isInBorderRow;

	/**
	 * Defines whether the collision position must be inside a border column
	 *
	 * @var Bool $isInBorderColumn
	 */
	private $isInBorderColumn;


	// Magic Methods

	/**
	 * CollisionSymbolDefinition constructor.
	 *
	 * @param String $_collisionSymbol The collision symbol
	 * @param CollisionDirection[] $_collisionDirections The collision directions for which the collision symbol may be used
	 * @param array $_positions The positions inside the border for which the collision symbol may be used ("start", "center" and/or "end")
	 * @param Bool $_isInBorderRow Defines whether the collision position must be inside a border row
	 * @param Bool $_isInBorderColumn Defines whether the collision position must be inside a border column
	 */
	public function __construct(String $_collisionSymbol, array $_collisionDirections, array $_positions, Bool $_isInBorderRow = true, Bool $_isInBorderColumn = true)
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

		$this->isInBorderRow = $_isInBorderRow;
		$this->isInBorderColumn = $_isInBorderColumn;
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

	/**
	 * Returns whether the collision position must be inside a border row.
	 *
	 * @return Bool True if the collision position must be inside a border row, false otherwise
	 */
	public function isInBorderRow(): Bool
	{
		return $this->isInBorderRow;
	}

	/**
	 * Returns whether the collision position must be inside a border column.
	 *
	 * @return Bool True if the collision position must be inside a border column, false otherwise
	 */
	public function isInBorderColumn(): Bool
	{
		return $this->isInBorderColumn;
	}


	// Class Methods

	/**
	 * Returns whether a specific collision matches this collision symbol definition.
	 *
	 * @param TextBorderPart $_dominatingBorderPart The dominating border part
	 * @param TextBorderPartCollisionPosition $_collisionPosition The collision position
	 *
	 * @return Bool True if the collision matches this collision symbol definition, false otherwise
	 */
	public function matchesCollision(TextBorderPart $_dominatingBorderPart, TextBorderPartCollisionPosition $_collisionPosition): Bool
	{
		$collisionPositionMatches = false;

		// Check whether the collision position is inside a border row
		if ($this->isInBorderRow && $_collisionPosition->y() % 2 == 0 ||
		    ! $this->isInBorderRow && $_collisionPosition->y() % 2 == 1)
		{
			// Check whether the collision position is inside a border column
			if ($this->isInBorderColumn && $_collisionPosition->x() % 2 == 0 ||
				! $this->isInBorderColumn && $_collisionPosition->x() % 2 == 1)
			{
				$collisionPositionMatches = true;
			}
		}

		$collisionPositionInsideBorderMatches = false;
		if ($collisionPositionMatches)
		{ // Check whether the position inside the border matches

			$isStartPosition = $_dominatingBorderPart->startsAt()->equals($_collisionPosition);
			$isEndPosition = $_dominatingBorderPart->endsAt()->equals($_collisionPosition);
			$isCenterPosition = (! $isStartPosition && ! $isEndPosition);

			if ($isStartPosition && $this->isStartPosition ||
				$isCenterPosition && $this->isCenterPosition ||
				$isEndPosition && $this->isEndPosition)
			{
				$collisionPositionInsideBorderMatches = true;
			}
		}

		$collisionDirectionMatches = false;
		if ($collisionPositionInsideBorderMatches)
		{
			foreach ($this->collisionDirections as $collisionDirection)
			{
				if ($_collisionPosition->collisionDirection()->equals($collisionDirection))
				{ // The collision direction matches
					$collisionDirectionMatches = true;
					break;
				}
			}
		}

		return $collisionDirectionMatches;
	}
}
