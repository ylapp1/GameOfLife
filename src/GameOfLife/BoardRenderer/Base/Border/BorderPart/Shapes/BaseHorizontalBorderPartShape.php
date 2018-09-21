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
 * Base class for horizontal border part shapes.
 */
abstract class BaseHorizontalBorderPartShape extends BaseBorderPartShape
{
	// Class Methods

	/**
	 * Calculates and returns the border part grid positions.
	 *
	 * @return Coordinate[] The border part grid positions
	 */
	protected function getBorderPartGridPositions(): array
	{
		$borderPartGridPositions = array();

		$startY = $this->parentBorderPart->startsAt()->y();
		for ($x = $this->parentBorderPart->startsAt()->x(); $x <= $this->parentBorderPart->endsAt()->x(); $x++)
		{
			$borderPartGridPositions[] = new Coordinate($x, $startY);
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
	 * Returns the positions at which the parent border part collides with another border part.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return Coordinate[] The positions at which the parent border part collides with the other border part
	 */
	public function getCollisionPositionsWith($_borderPart): array
	{
		if ($_borderPart->shape() instanceof BaseHorizontalBorderPartShape &&
			$_borderPart->startsAt()->y() != $this->parentBorderPart->startsAt()->y())
		{ // Other horizontal border parts must be in the same row as this parent border part
			return array();
		}
		elseif ($_borderPart->shape() instanceof BaseVerticalBorderPartShape &&
			    ($_borderPart->startsAt()->y() > $this->parentBorderPart->startsAt()->y() ||
				$_borderPart->endsAt()->y() < $this->parentBorderPart->startsAt()->y()))
		{
			return array();
		}
		else return parent::getCollisionPositionsWith($_borderPart);
	}
}