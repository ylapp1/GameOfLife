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
 * Class for horizontal border parts.
 */
abstract class HorizontalBorderPartShape extends BaseBorderPartShape
{
	// Class Methods

    /**
     * Calculates and returns the length of the parent border part with this shape with start and end edges.
     *
     * @return int The length of this border part without start and end edges
     */
    public function getTotalLength(): int
    {
        return $this->parentBorderPart->endsAt()->x() - $this->parentBorderPart->startsAt()->x() + 1;
    }

    /**
     * Returns the position at which the parent border part collides with another border part or null if there is no collision.
     *
     * @param BaseBorderPart $_borderPart The other border part
     *
     * @return int|null The position at which the parent border part collides with the other border part or null if there is no collision
     */
    public function collidesWith($_borderPart)
    {
        $startX = $this->parentBorderPart->startsAt()->x();
        $startY = $this->parentBorderPart->startsAt()->y();

        for ($x = $startX; $x <= $this->parentBorderPart->endsAt()->x(); $x++)
        {
            if ($_borderPart->containsCoordinate(new Coordinate($x, $startY)))
            {
                return $x - $startX;
            }
        }

        return null;
    }

    /**
     * Returns whether the parent border part contains a specific coordinate.
     *
     * @param Coordinate $_coordinate The coordinate
     *
     * @return Bool True if the parent border part contains the coordinate, false otherwise
     */
    public function containsCoordinate(Coordinate $_coordinate): Bool
    {
        if ($_coordinate->y() == $this->parentBorderPart->startsAt()->y())
        {
            if ($_coordinate->x() >= $this->parentBorderPart->startsAt()->x() &&
                $_coordinate->x() <= $this->parentBorderPart->endsAt()->x())
            {
                return true;
            }
        }

        return false;
    }
}
