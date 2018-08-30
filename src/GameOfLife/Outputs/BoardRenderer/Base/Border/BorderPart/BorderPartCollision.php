<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart;

use GameOfLife\Coordinate;

/**
 * Stores information about a border part collision.
 */
class BorderPartCollision
{
    // Attributes

	/**
	 * The positions of the border part collision inside the border grid
	 *
	 * @var Coordinate[] $position
	 */
	private $positions;

	/**
	 * The colliding border part
	 *
	 * @var BaseBorderPart $with
	 */
	private $with;

	/**
	 * Indicates whether the colliding border part is an outer border
	 *
	 * @var Bool $isOuterBorderPartCollision
	 */
	private $isOuterBorderPartCollision;


	// Magic Methods

	/**
	 * BorderPartCollision constructor.
	 *
	 * @param Coordinate[] $_positions The positions of the collision inside the border grid
	 * @param BaseBorderPart $_with The colliding border part
	 * @param bool $_isOuterBorderCollision Indicates whether the colliding border part is an outer border
	 */
	public function __construct(array $_positions, $_with, Bool $_isOuterBorderCollision)
	{
		$this->positions = $_positions;
		$this->with = $_with;
		$this->isOuterBorderPartCollision = $_isOuterBorderCollision;
	}


	// Getters and Setters

    /**
     * Returns the positions of the collision inside the border grid.
     *
     * @return Coordinate[] The positions of the collision inside the border grid
     */
	public function positions(): array
    {
        return $this->positions;
    }

    /**
     * Returns the colliding border part.
     *
     * @return BaseBorderPart The colliding border part
     */
    public function with()
    {
        return $this->with;
    }

    /**
     * Returns whether the colliding border is an outer border.
     *
     * @return Bool True if the colliding border is an outer border, false otherwise
     */
    public function isOuterBorderPartCollision(): Bool
    {
        return $this->isOuterBorderPartCollision;
    }
}
