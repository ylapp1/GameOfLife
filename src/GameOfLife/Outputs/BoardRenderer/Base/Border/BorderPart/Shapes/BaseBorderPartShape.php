<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border\BorderPart\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\BaseCanvas;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

/**
 * Stores information about a specific border part shape.
 */
abstract class BaseBorderPartShape
{
    /**
     * The parent border part of this border part shape
     *
     * @var BaseBorderPart $parentBorderPart
     */
    protected $parentBorderPart;


    // Getters and Setters

    /**
     * @return BaseBorderPart
     */
    public function parentBorderPart()
    {
        return $this->parentBorderPart;
    }

    /**
     * Sets the parent border part of this border part shape.
     *
     * @param BaseBorderPart $_parentBorderPart The parent border part of this border part shape
     */
    public function setParentBorder($_parentBorderPart)
    {
        $this->parentBorderPart = $_parentBorderPart;
    }


    // Class Methods

    /**
     * Calculates and returns the length of the parent border part with this shape with start and end edges.
     *
     * @return int The length of this border part without start and end edges
     */
    abstract public function getTotalLength(): int;

    /**
     * Returns the position at which the parent border part collides with another border part or null if there is no collision.
     *
     * @param BaseBorderPart $_borderPart The other border part
     *
     * @return Coordinate|null The position at which the parent border part collides with the other border part or null if there is no collision
     */
    abstract public function collidesWith($_borderPart);

    /**
     * Returns whether the parent border part contains a specific coordinate.
     *
     * @param Coordinate $_coordinate The coordinate
     *
     * @return Bool True if the parent border part contains the coordinate, false otherwise
     */
    abstract public function containsCoordinate(Coordinate $_coordinate): Bool;

	/**
	 * Returns the distance of a coordinate to the start of the parent border part.
	 * If the coordinate is not inside the border this function will return null.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return int The distance of the coordinate to the start of the parent border part or null if the coordinate is not inside the border
	 */
    abstract public function getCoordinatePosition(Coordinate $_coordinate);

    /**
     * Draws the parent border part to a canvas.
     *
     * @param BaseCanvas $_canvas The canvas
     *
     * TODO: Use getRenderedBorderPart instead
     */
    abstract public function addBorderPartToCanvas($_canvas);
}
