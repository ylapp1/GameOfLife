<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border\BorderPart\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

/**
 * Defines the shape of a border part.
 *
 * Call setParentBorderPart() before using other methods of this class
 * Call getRenderedBorderPart() to get the rendered parent border part
 */
abstract class BaseBorderPartShape
{
	// Attributes

    /**
     * The parent border part
     *
     * @var BaseBorderPart $parentBorderPart
     */
    protected $parentBorderPart;


    // Getters and setters

	/**
	 * Sets the parent border part.
	 *
	 * @param BaseBorderPart $_parentBorderPart The parent border part
	 */
	public function setParentBorderPart($_parentBorderPart)
	{
		$this->parentBorderPart = $_parentBorderPart;
	}


    // Class Methods

	/**
	 * Returns whether the parent border part contains a specific coordinate.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the parent border part contains the coordinate, false otherwise
	 */
	abstract public function containsCoordinate(Coordinate $_coordinate): Bool;

    /**
     * Returns the position at which the parent border part collides with another border part or null if there is no collision.
     *
     * @param BaseBorderPart $_borderPart The other border part
     *
     * @return Coordinate|null The position at which the parent border part collides with the other border part or null if there is no collision
     */
    abstract public function getCollisionPositionWith($_borderPart);

    /**
     * Creates and returns the rendered parent border part.
     *
     * @return mixed The rendered parent border part
     */
    abstract public function getRenderedBorderPart();
}
