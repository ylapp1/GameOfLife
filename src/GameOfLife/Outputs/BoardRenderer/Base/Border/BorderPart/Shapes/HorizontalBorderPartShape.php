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
        return $this->parentBorderPart->endsAt()->x() - $this->parentBorderPart->startsAt()->x() + 2;
    }

    /**
     * Returns the position at which the parent border part collides with another border part or null if there is no collision.
     *
     * @param BaseBorderPart $_borderPart The other border part
     *
     * @return Coordinate|null The position at which the parent border part collides with the other border part or null if there is no collision
     */
    public function collidesWith($_borderPart)
    {
        $startX = $this->parentBorderPart->startsAt()->x();
	    $checkCoordinate = clone $this->parentBorderPart->startsAt();

	    for ($x = $startX; $x <= $this->parentBorderPart->endsAt()->x(); $x++)
        {
	        $checkCoordinate->setX($x);
            if ($_borderPart->containsCoordinate($checkCoordinate))
            {
                return $checkCoordinate;
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

	/**
	 * Returns the distance of a coordinate to the start of the parent border part.
	 * If the coordinate is not inside the border this function will return null.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return int The distance of the coordinate to the start of the parent border part or null if the coordinate is not inside the border
	 */
    public function getCoordinatePosition(Coordinate $_coordinate)
    {
    	if ($this->containsCoordinate($_coordinate))
	    {
	    	return $_coordinate->x() - $this->parentBorderPart->startsAt()->x();
	    }
    	else return null;
    }
}
