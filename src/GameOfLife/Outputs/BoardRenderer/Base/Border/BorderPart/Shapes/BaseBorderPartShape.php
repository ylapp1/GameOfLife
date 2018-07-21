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

    /**
     * BaseBorderPartShape constructor.
     *
     * @param BaseBorderPart $_parentBorderPart The parent border part of this border part shape
     */
    protected function __construct($_parentBorderPart)
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
     * @return int|null The position at which the parent border part collides with the other border part or null if there is no collision
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
}
