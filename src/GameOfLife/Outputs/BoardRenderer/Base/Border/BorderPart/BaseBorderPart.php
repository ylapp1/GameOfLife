<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseHorizontalBorderPartShape;
use GameOfLife\Coordinate;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;

/**
 * Container class that stores information about a border part.
 * A border part is a single line of the total border with a fixed start and end point, for example horizontal, vertical,
 * diagonal or curved lines.
 */
abstract class BaseBorderPart
{
	// Attributes

	/**
	 * The start coordinate
	 *
	 * @var Coordinate $startsAt
	 */
	protected $startsAt;

	/**
	 * The end coordinate
	 *
	 * @var Coordinate $endsAt
	 */
	protected $endsAt;

	/**
	 * The shape
	 *
	 * @var BaseBorderPartShape $shape
	 */
	protected $shape;

    /**
     * The parent border
     *
     * @var BaseBorder $parentBorder
     */
	protected $parentBorder;

	/**
	 * The list of own collisions with other border parts
	 * These are the collisions with border parts that existed before this border part was added
	 *
	 * @var BorderPartCollision[] $ownCollisions
	 */
	protected $ownCollisions;

	/**
	 * The list of other borders collisions with this border
	 * These are the collisions with border parts that were added after this border part
	 *
	 * @var BorderPartCollision[] $otherBorderPartCollisions
	 */
	protected $otherBorderPartCollisions;


	// Magic Methods

	/**
	 * BaseBorderPart constructor.
	 *
     * @param BaseBorder $_parentBorder The parent border
	 * @param Coordinate $_startsAt The start coordinate of this border part
	 * @param Coordinate $_endsAt The end coordinate of this border part
     * @param BaseBorderPartShape $_shape The shape of this border part
	 */
	protected function __construct($_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, $_shape)
    {
        $this->parentBorder = $_parentBorder;
    	$this->startsAt = $_startsAt;
    	$this->endsAt = $_endsAt;
    	$this->shape = $_shape;
    	$this->shape->setParentBorderPart($this);
	    $this->ownCollisions = array();
	    $this->otherBorderPartCollisions = array();
    }


    // Getters and Setters

	/**
	 * Returns the start coordinate.
	 *
	 * @return Coordinate The start coordinate
	 */
	public function startsAt(): Coordinate
	{
		return $this->startsAt;
	}

	/**
	 * Returns the end coordinate.
	 *
	 * @return Coordinate The end coordinate
	 */
	public function endsAt(): Coordinate
	{
		return $this->endsAt;
	}

    /**
     * Returns the parent border.
     *
     * @return BaseBorder The parent border
     */
	public function parentBorder()
    {
        return $this->parentBorder;
    }

	/**
	 * Returns the border part shape.
	 *
	 * @return BaseBorderPartShape The border part shape
	 */
    public function shape()
    {
    	return $this->shape;
    }


	// Class Methods

	/**
	 * Creates and returns the rendered border part.
	 *
	 * @return RenderedBorderPart The rendered border part
	 */
	public function getRenderedBorderPart()
    {
    	return $this->shape->getRenderedBorderPart();
    }

	/**
	 * Adds a border part collision of an other border part with this border part.
	 *
	 * @param Coordinate $_at The collision position
	 * @param BaseBorderPart $_otherBorderPart The other border part
	 */
    public function addOtherBorderPartCollision(Coordinate $_at, $_otherBorderPart)
    {
    	$this->otherBorderPartCollisions[] = new BorderPartCollision($_at, $_otherBorderPart, $this->isOuterBorderPart($_otherBorderPart));
    }

	/**
	 * Returns whether another border part is an outer border part relative to this border part.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return Bool True if the other border part is an outer border part, false otherwise
	 */
    public function isOuterBorderPart($_borderPart): Bool
    {
	    // Check whether the other border part is a inner or outer border part
	    if ($this->parentBorder() === $_borderPart->parentBorder() ||
		    $this->parentBorder()->containsBorder($_borderPart->parentBorder()))
	    {
		    return false;
	    }
	    else return true;
    }

	/**
	 * Checks whether this border part collides with another border part and adds a border part collision to this border
	 * part if a collision was detected.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 */
	public function checkCollisionWith($_borderPart)
	{
		$collisionPosition = $this->shape->getCollisionPositionWith($_borderPart);
		if ($collisionPosition !== null)
		{
			$this->ownCollisions[] = new BorderPartCollision($collisionPosition, $_borderPart, $this->isOuterBorderPart($_borderPart));
			$_borderPart->addOtherBorderPartCollision($collisionPosition, $this);
		}
	}

	/**
	 * Returns all collision positions of this border part.
	 *
	 * @return Coordinate[] The collision positions
	 */
	public function getCollisionPositions(): array
	{
		/** @var Coordinate[] $collisionPositions */
		$collisionPositions = array();

		/** @var BorderPartCollision[] $allCollisions */
		$allCollisions = array_merge($this->ownCollisions, $this->otherBorderPartCollisions);

		foreach ($allCollisions as $collision)
		{
			$isExistingPosition = false;
			foreach ($collisionPositions as $collisionPosition)
			{
				if ($collisionPosition->equals($collision->position()))
				{
					$isExistingPosition = true;
					break;
				}
			}

			if (! $isExistingPosition) $collisionPositions[] = $collision->position();
		}

		return $collisionPositions;
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
