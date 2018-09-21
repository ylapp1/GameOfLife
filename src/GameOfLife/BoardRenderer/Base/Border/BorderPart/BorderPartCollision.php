<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart;

use Util\Geometry\Coordinate;

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
		$this->positions = $this->summarizeCollisionPositions($_positions);
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


    // Class Methods

	/**
	 * Summarizes a list of collision positions.
	 *
	 * This is done with the following pattern:
	 * 1) The first coordinate is kept if it is a border row and border column coordinate
	 * 2) The last coordinate is kept if it is a border row and border column coordinate
	 * 3) The middle border row/column coordinates are discarded
	 *
	 * @param Coordinate[] $_positions The collision positions
	 *
	 * @return Coordinate[] The summarized list of collision positions
	 */
	private function summarizeCollisionPositions(array $_positions): array
	{
		$summarizedCoordinates = array();
		if ($_positions)
		{
			if ($_positions[0]->x() % 2 == 0 && $_positions[0]->y() % 2 == 0)
			{ // First coordinate is a border row and border column coordinate
				$summarizedCoordinates[] = array_shift($_positions);
			}

			if ($_positions)
			{ // There are more positions left

				// Check the last coordinate
				$lastCoordinate = array_pop($_positions);
				if ($lastCoordinate && $lastCoordinate->x() % 2 == 0 && $lastCoordinate->y() % 2 == 0)
				{ // Last coordinate is a border row and border column coordinate
					$summarizedCoordinates[] = $lastCoordinate;
				}
				else $_positions[] = $lastCoordinate;

				// Summarize the middle positions
				for ($i = 0; $i < count($_positions); $i++)
				{
					if ($_positions[$i]->x() % 2 != 0 || $_positions[$i]->y() % 2 != 0)
					{ // The coordinate is either only a border column or only a border row coordinate
						$summarizedCoordinates[] = $_positions[$i];
					}
				}
			}
		}

		return $summarizedCoordinates;
	}
}
