<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border\BorderPart;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\BaseCanvas;
use Output\BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;
use Output\BoardRenderer\Base\Border\Shapes\BaseBorderShape;

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

    /**
     * The parent border shape
     *
     * @var BaseBorderShape $parentBorderShape
     */
	protected $parentBorderShape;


	// Magic Methods

	/**
	 * BaseBorderPart constructor.
	 *
     * @param BaseBorderShape $_parentBorderShape The parent border shape
	 * @param Coordinate $_startsAt The start coordinate of this border
	 * @param Coordinate $_endsAt The end coordinate of this border
     * @param BaseBorderPartShape $_shape The shape of this border part
	 */
	protected function __construct($_parentBorderShape, Coordinate $_startsAt, Coordinate $_endsAt, $_shape)
    {
        $this->parentBorderShape = $_parentBorderShape;
    	$this->startsAt = $_startsAt;
    	$this->endsAt = $_endsAt;
    	$this->shape = $_shape;
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

	/**
	 * Calculates and returns the length of this border part with start and end edges.
	 *
	 * @return int The length of this border part without start and end edges
	 */
	public function getTotalLength(): int
    {
        return $this->shape->getTotalLength();
    }

	/**
	 * Checks whether this border part collides with another border part and adds border part collisions to this border
	 * part if a collision was detected.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 */
	public function checkCollisionWith(BaseBorderPart $_borderPart)
	{
		$collisionPosition = $this->shape->collidesWith($_borderPart);
		if ($collisionPosition !== null)
		{
			// TODO: Fix border parts overlapping case

            $this->parentBorderShape->parentBorder()->containsInnerBorder($_borderPart)

			// If this parent border parent border has the child "collided border" then isInnerCollision
			// Else is outer collision
			$isOuterBorderPart = true; // TODO: Fix is outer board detection
			$this->collisions[] = new BorderPartCollision($collisionPosition, $_borderPart, $isOuterBorderPart);
		}
	}

	/**
	 * Renders this border part and adds it to a symbol grid.
	 *
	 * @param BaseCanvas $_canvas The canvas
	 */
	public function addToCanvas($_canvas)
    {
        $this->shape->addBorderPartToCanvas($_canvas);
    }

	/**
	 * Returns whether the output border contains a specific coordinate between its left and right edge.
	 * This does not include the coordinates of the left and right edge.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the output border contains the coordinate, false otherwise
	 */
    public function containsCoordinateBetweenEdges(Coordinate $_coordinate): Bool
    {
        if ($_coordinate !== $this->startsAt() && $_coordinate !== $this->endsAt() &&
            $this->shape->containsCoordinate($_coordinate))
        {
            return true;
        }
        else return false;
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