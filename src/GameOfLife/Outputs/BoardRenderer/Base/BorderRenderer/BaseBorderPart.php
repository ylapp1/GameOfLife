<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use GameOfLife\Coordinate;
use Output\BoardPrinter\BaseRenderedBoard;
use Output\BoardRenderer\Base\BorderPartShapes\BaseBorderPartShape;

/**
 * Container that stores the information about a part of a border.
 */
abstract class BaseBorderPart
{
	// Attributes

	/**
	 * The start coordinate of this border
	 *
	 * @var Coordinate $startsAt
	 */
	protected $startsAt;

	/**
	 * The end coordinate of this border
	 *
	 * @var Coordinate $endsAt
	 */
	protected $endsAt;

	/**
	 * The shape of this border part
	 *
	 * @var BaseBorderPartShape $shape
	 */
	protected $shape;

	/**
	 * The list of collisions with other borders.
	 *
	 * @var BorderPartCollision[] $collisions
	 */
	protected $collisions;


	// Magic Methods

	/**
	 * BaseBorderPart constructor.
	 *
	 * @param Coordinate $_startsAt The start coordinate of this border
	 * @param Coordinate $_endsAt The end coordinate of this border
	 */
	protected function __construct(Coordinate $_startsAt, Coordinate $_endsAt)
    {
    	$this->startsAt = $_startsAt;
    	$this->endsAt = $_endsAt;
    }


    // Getters and Setters

	/**
	 * Returns the start coordinate of this border.
	 *
	 * @return Coordinate The start coordinate of this border
	 */
	public function startsAt(): Coordinate
	{
		return $this->startsAt;
	}

	/**
	 * Returns the end coordinate of this border
	 *
	 * @return Coordinate The end coordinate of this border
	 */
	public function endsAt(): Coordinate
	{
		return $this->endsAt;
	}


	// Class Methods


	// Edges

	/**
	 * Returns whether this border part has a start edge.
	 *
	 * @return Bool True if this border part has a start edge, false otherwise
	 */
	abstract public function hasStartEdge(): Bool;

	/**
	 * Returns whether this border part has a end edge.
	 *
	 * @return Bool True if this border part has a end edge, false otherwise
	 */
	abstract public function hasEndEdge(): Bool;


	// Border length

	/**
	 * Calculates and returns the length of this border part with start and end edges.
	 *
	 * @return int The length of this border part without start and end edges
	 */
	abstract public function getTotalLength(): int;

	/**
	 * Calculates and returns the length of this border part without start and end edges.
	 *
	 * @return int The length of this border part without start and end edges
	 */
	public function getLengthWithoutEdges(): int
	{
		return $this->getTotalLength() - (int)$this->hasStartEdge() - (int)$this->hasEndEdge();
	}


	// Collisions

	/**
	 * Returns the position at which this border part collides with another border part or null if there is no collision.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return int|null The position at which this border part collides with the other border part or null if there is no collision
	 */
	abstract protected function collidesWith(BaseBorderPart $_borderPart);

	/**
	 * Checks whether this border part collides with another border part and adds border part collisions to this border
	 * part if a collision was detected.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 */
	public function checkCollisionWith(BaseBorderPart $_borderPart)
	{
		$collisionPosition = $this->collidesWith($_borderPart);
		if ($collisionPosition !== null)
		{
			// TODO: Fix border parts overlapping case

			// If this parent border parent border has the child "collided border" then isInnerCollision
			// Else is outer collision
			$isOuterBorderPart = true;
			$this->collisions[] = new BorderPartCollision($collisionPosition, $_borderPart, $isOuterBorderPart);
		}
	}

	/**
	 * Renders this border part and adds it to a rendered board.
	 *
	 * @param BaseRenderedBoard $_renderedBoard The rendered board
	 */
	abstract public function addToRenderedBoard(BaseRenderedBoard $_renderedBoard);

	/**
	 * Returns whether the output border contains a specific coordinate between its left and right edge.
	 * This does not include the coordinates of the left and right edge.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the output border contains the coordinate, false otherwise
	 */
    abstract public function containsCoordinateBetweenEdges(Coordinate $_coordinate): Bool;
}
