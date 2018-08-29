<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;
use BoardRenderer\Base\Border\Shapes\BaseBorderShape;
use GameOfLife\Coordinate;

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
     * The parent border shape
     *
     * @var BaseBorderShape $parentBorderShape
     */
	protected $parentBorderShape;

	/**
	 * The list of own collisions with other border parts
	 * These are the collisions with border parts that existed before this border part was added
	 *
	 * @var BorderPartCollision[] $ownCollisions
	 */
	protected $ownCollisions;

	/**
	 * The list of other border parts collisions with this border part
	 * These are the collisions with border parts that were added after this border part
	 *
	 * @var BorderPartCollision[] $otherBorderPartCollisions
	 */
	protected $otherBorderPartCollisions;

	/**
	 * The thickness of the border in symbols, pixels, etc.
	 *
	 * @var BorderPartThickness $thickness
	 */
	protected $thickness;


	// Magic Methods

	/**
	 * BaseBorderPart constructor.
	 *
     * @param BaseBorderShape $_parentBorderShape The parent border shape
	 * @param Coordinate $_startsAt The start coordinate
	 * @param Coordinate $_endsAt The end coordinate
     * @param BaseBorderPartShape $_shape The shape of this border part
	 * @param BorderPartThickness $_thickness The thickness of the border part in symbols, pixels, etc.
	 */
	public function __construct($_parentBorderShape, Coordinate $_startsAt, Coordinate $_endsAt, $_shape, BorderPartThickness $_thickness)
    {
        $this->parentBorderShape = $_parentBorderShape;
    	$this->startsAt = $_startsAt;
    	$this->endsAt = $_endsAt;
    	$this->shape = $_shape;
    	$this->thickness = $_thickness;
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
	 * Returns the parent border shape.
	 *
	 * @return BaseBorderShape The parent border shape
	 */
	public function parentBorderShape()
	{
		return $this->parentBorderShape;
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

	/**
	 * Returns the thickness of this border part.
	 *
	 * @return BorderPartThickness The thickness of this border part
	 */
    public function thickness(): BorderPartThickness
    {
    	return $this->thickness;
    }

	/**
	 * Returns the collisions of this border part with other border parts that existed before this border part was added.
	 *
	 * @return BorderPartCollision[] The list of own border part collisions
	 */
    public function ownCollisions(): array
    {
    	return $this->ownCollisions;
    }

	/**
	 * Returns all collisions of this border part.
	 *
	 * @return BorderPartCollision[] The list of border part collisions
	 */
    public function collisions(): array
    {
    	return array_merge($this->ownCollisions, $this->otherBorderPartCollisions);
    }


	// Class Methods

	/**
	 * Creates and returns the rendered border part.
	 *
	 * @param int $_fieldSize The size of a single field in pixels, symbols, etc.
	 *
	 * @return RenderedBorderPart The rendered border part
	 */
	public function getRenderedBorderPart(int $_fieldSize): RenderedBorderPart
    {
    	return $this->shape->getRenderedBorderPart($_fieldSize);
    }

	/**
	 * Adds a border part collision of another border part with this border part.
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
	 * This is done by checking whether the parent border contains the parent border of the other border part.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return Bool True if the other border part is an outer border part, false otherwise
	 */
    public function isOuterBorderPart($_borderPart): Bool
    {
	    if ($this->parentBorderShape->parentBorder()->containsBorder($_borderPart->parentBorderShape()->parentBorder()))
	    {
		    return false;
	    }
	    else return true;
    }

	/**
	 * Checks whether this border part collides with another border part and adds a border part collision to this border
	 * part and the other border part if a collision was detected.
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
	 * Returns the maximum collision thicknesses at each collision position of this border part.
	 *
	 * @return BorderPartThickness[] The collision positions
	 */
	public function getCollisionThicknesses(): array
	{
		/** @var BorderPartThickness[] $collisionThicknesses */
		$collisionThicknesses = array();
		$processedCollisionPositions = array();

		foreach ($this->collisions() as $collision)
		{
			$isExistingCollisionPosition = false;
			foreach ($processedCollisionPositions as $index => $collisionPosition)
			{
				if ($collisionPosition->equals($collision->position()))
				{
					if ($collisionThicknesses[$index]->height() < $collision->with()->thickness()->height())
					{
						$collisionThicknesses[$index]->setHeight($collision->with()->thickness()->height());
					}
					if ($collisionThicknesses[$index]->width() < $collision->with()->thickness()->width())
					{
						$collisionThicknesses[$index]->setWidth($collision->with()->thickness()->width());
					}
					$isExistingCollisionPosition = true;
					break;
				}
			}

			if (! $isExistingCollisionPosition)
			{
				$collisionThicknesses[] = clone $collision->with()->thickness();
				$processedCollisionPositions[] = $collision->position();
			}
		}

		return $collisionThicknesses;
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
