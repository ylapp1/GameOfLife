<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Collision\Border\BorderPart;

use GameOfLife\Coordinate;

/**
 * Stores information about a border part collision.
 */
class BorderPartCollision
{
    // Attributes

	/**
	 * The position of the border part collision inside the border grid
	 *
	 * @var Coordinate $position
	 */
	private $position;

	/**
	 * The colliding border part
	 *
	 * @var CollisionBorderPart $with
	 */
	private $with;

	/**
	 * Indicates whether the colliding border part is a outer border
	 *
	 * @var Bool $isOuterBorderPartCollision
	 */
	private $isOuterBorderPartCollision;


	// Magic Methods

	/**
	 * BorderPartCollision constructor.
	 *
	 * @param Coordinate $_position The position of the collision inside the border relative from the start position of the border
	 * @param CollisionBorderPart $_with The reference to the border that collides
	 * @param bool $_isOuterBorderCollision Indicates whether the colliding border is an outer border
	 */
	public function __construct(Coordinate $_position, $_with, Bool $_isOuterBorderCollision)
	{
		$this->position = $_position;
		$this->with = $_with;
		$this->isOuterBorderPartCollision = $_isOuterBorderCollision;
	}


	// Getters and Setters

    /**
     * Returns the position of the collision inside the border relative from the start position of the border.
     *
     * @return Coordinate The position of the collision inside the border relative from the start position of the border
     */
	public function position()
    {
        return $this->position;
    }

    /**
     * Returns the colliding border part.
     *
     * @return CollisionBorderPart The colliding border part
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
