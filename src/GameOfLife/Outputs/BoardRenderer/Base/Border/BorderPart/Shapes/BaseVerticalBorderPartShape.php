<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use GameOfLife\Coordinate;

/**
 * Base class for vertical border part shapes.
 */
abstract class BaseVerticalBorderPartShape extends BaseBorderPartShape
{
	// Class Methods

	/**
	 * Returns the border part grid positions for the parent border part.
	 *
	 * @return Coordinate[] The border part grid positions
	 */
	protected function getBorderPartGridPositions(): array
	{
		$borderPartGridPositions = array();

		$startX = $this->parentBorderPart->startsAt()->x();
		for ($y = $this->parentBorderPart->startsAt()->y(); $y < $this->parentBorderPart->endsAt()->y(); $y++)
		{
			$borderPartGridPositions[] = new Coordinate($startX, $y);
		}

		return $borderPartGridPositions;
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
		if ($_coordinate->x() == $this->parentBorderPart->startsAt()->x())
		{
			if ($_coordinate->y() >= $this->parentBorderPart->startsAt()->y() &&
				$_coordinate->y() <= $this->parentBorderPart->endsAt()->y())
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the position sat which the parent border part collides with another border part.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return Coordinate[] The positions at which the parent border part collides with the other border part
	 */
	public function getCollisionPositionsWith($_borderPart): array
	{
		if ($_borderPart->shape() instanceof BaseVerticalBorderPartShape &&
			! $_borderPart->startsAt()->x() != $this->parentBorderPart->startsAt()->x())
		{ // Other vertical border parts must be in the same column as this parent border part
			return array();
		}
		elseif ($_borderPart->shape() instanceof BaseHorizontalBorderPartShape &&
			    ($_borderPart->startsAt()->x() > $this->parentBorderPart->startsAt()->x() ||
		         $_borderPart->endsAt()->x() < $this->parentBorderPart->startsAt()->x()))
		{
			return array();
		}

		$collisionPositions = array();
		$checkCoordinate = clone $this->parentBorderPart->startsAt();

		for ($y = $this->parentBorderPart->startsAt()->y(); $y <= $this->parentBorderPart->endsAt()->y(); $y++)
		{
			$checkCoordinate->setY($y);
			if ($_borderPart->containsCoordinate($checkCoordinate))
			{
				$collisionPositions[] = clone $checkCoordinate;
			}
		}

		return $collisionPositions;
	}
}
