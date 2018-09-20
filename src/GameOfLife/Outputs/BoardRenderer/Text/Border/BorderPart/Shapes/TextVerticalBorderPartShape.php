<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseVerticalBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextBorderPartCollisionPosition;
use GameOfLife\Coordinate;

/**
 * Shape for vertical text border parts.
 */
class TextVerticalBorderPartShape extends BaseVerticalBorderPartShape implements TextBorderPartShapeInterface
{
	use TextBorderPartShapeTrait;

	// Attributes

	/**
	 * The parent border part
	 *
	 * @var TextBorderPart $parentBorderPart
	 */
	protected $parentBorderPart;


	// Class Methods

	/**
	 * Calculates and returns the number of border symbols that are necessary to render the parent border part with this shape not including start and end edges.
	 *
	 * @return int The number of border symbols that are necessary to render the parent border part with this shape not including start and end edges
	 */
	public function getNumberOfBorderSymbols(): int
	{
		return ($this->parentBorderPart->endsAt()->y() - $this->parentBorderPart->startsAt()->y()) / 2;
	}

	/**
	 * Returns the position of a coordinate inside the list of border symbols of the parent border part.
	 *
	 * @param Coordinate $_gridPosition The grid position
	 *
	 * @return int|null The position of the coordinate inside the list of border symbols of the parent border part or null if the coordinate is not inside the parent border part
	 */
	public function getBorderSymbolPositionOf(Coordinate $_gridPosition)
	{
		if ($this->containsCoordinate($_gridPosition)) return $_gridPosition->y() - $this->parentBorderPart->startsAt()->y();
		else return null;
	}

	/**
	 * Calculates and returns the border part grid positions without collision positions.
	 *
	 * @return Coordinate[] The border part grid positions
	 */
	public function getBorderPartGridPositions(): array
	{
		$coordinates = parent::getBorderPartGridPositions();
		$borderPartGridPositions = array();

		// Add border positions
		$borderPartGridPositions[] = clone $this->parentBorderPart->startsAt();
		foreach ($coordinates as $coordinate)
		{
			// Add all coordinates that are inside board field rows
			if ($coordinate->y() % 2 != 0) $borderPartGridPositions[] = $coordinate;
		}
		$borderPartGridPositions[] = clone $this->parentBorderPart->endsAt();

		return $borderPartGridPositions;
	}

	/**
	 * Returns the border part grid positions of the rendered border part with collision positions.
	 *
	 * @return Coordinate[] The border part grid positions of the rendered border part
	 */
	protected function getRenderedBorderPartGridPositions(): array
	{
		$borderPartGridPositions = $this->getBorderPartGridPositions();

		// Add collision positions
		$borderPartGridPositions = array_merge(
			$borderPartGridPositions,
			$this->getCollisionGridPositions($borderPartGridPositions, $this->parentBorderPart->ownCollisions())
		);

		// Sort the coordinates by Y-Coordinate ascending
		usort($borderPartGridPositions,
			function (Coordinate $_a, Coordinate $_b)
			{
				if ($_a->y() > $_b->y()) return true;
				else return false;
			}
		);

		return $borderPartGridPositions;
	}

	/**
	 * Calculates and returns the border part grid positions at which the parent border can collide with another border part.
	 *
	 * @return Coordinate[] The possible collision positions
	 */
	public function getPossibleCollisionPositions(): array
	{
		/*
		 * Returns:
		 *
		 * 1) All border part grid positions that are filled by the parent border part
		 *    (These are possible collision positions for border parts that overlap the parent border part)
		 * 2) The positions in between the border part grid positions coordinates
		 *    (These are possible collision positions for border parts that collide at one point with the parent border part
		 */
		return parent::getBorderPartGridPositions();
	}

	/**
	 * Creates and returns the rendered parent border part.
	 *
	 * @param int $_fieldSize The field size in symbols
	 *
	 * @return String[] The border symbols of the parent border part
	 */
    public function getRawRenderedBorderPart(int $_fieldSize)
    {
        return $this->parentBorderPart->getBorderSymbols();
    }

	/**
	 * Returns the position inside the border grid where the parent border collides with another border part.
	 *
	 * @param TextBorderPart $_borderPart The other border part
	 *
	 * @return TextBorderPartCollisionPosition[] The positions
	 */
	public function getCollisionPositionsWith($_borderPart): array
	{
		$collisionPositions = parent::getCollisionPositionsWith($_borderPart);

		if ($collisionPositions)
		{
			$textBorderPartCollisionPositions = array();

			foreach ($collisionPositions as $collisionPosition)
			{
				if ($this->parentBorderPart->isOuterBorderPart($_borderPart)) $inferiorBorderPart = $this->parentBorderPart;
				else $inferiorBorderPart = $_borderPart;

				$textBorderPartCollisionPositions[] = $this->getTextBorderPartCollisionPosition($collisionPosition, $inferiorBorderPart);
			}

			return $textBorderPartCollisionPositions;
		}
		else return array();
	}
}
