<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPartCollision;
use BoardRenderer\Text\Border\BorderPart\CollisionDirection;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextBorderPartCollisionPosition;
use Utils\Geometry\Coordinate;

/**
 * Additional methods for text border part shapes.
 */
trait TextBorderPartShapeTrait
{
	/**
	 * Returns the positions of the collision positions on the border grid.
	 *
	 * @param TextBorderPartGridPosition[] $_borderSymbolPositions The border symbol grid positions
	 * @param BorderPartCollision[] $_collisions The collisions
	 *
	 * @return TextBorderPartGridPosition[] The collision grid positions
	 */
	public function getCollisionGridPositions(array $_borderSymbolPositions, array $_collisions): array
	{
		$collisionGridPositions = array();

		foreach ($_collisions as $collision)
		{
			foreach ($collision->positions() as $collisionPosition)
			{
				$positionExists = false;
				foreach ($_borderSymbolPositions as $borderSymbolPosition)
				{
					if ($borderSymbolPosition->equals($collisionPosition))
					{
						$positionExists = true;
						break;
					}
				}

				if (! $positionExists) $collisionGridPositions[] = $collisionPosition;
			}
		}

		return $collisionGridPositions;
	}

	/**
	 * Returns the text border part collision position for a border part collision.
	 *
	 * @param Coordinate $_at The coordinate at which the border parts collide
	 * @param TextBorderPart $_inferiorBorderPart The inferior border part (The border part whose collision symbols will be ignored)
	 *
	 * @return TextBorderPartCollisionPosition The text border part collision position
	 */
	protected function getTextBorderPartCollisionPosition(Coordinate $_at, TextBorderPart $_inferiorBorderPart): TextBorderPartCollisionPosition
	{
		$collisionDirection = new CollisionDirection($this->getCollisionDirections($_at, $_inferiorBorderPart));

		$textBorderPartCollisionPosition = new TextBorderPartCollisionPosition(
			$_at,
			$collisionDirection
		);

		return $textBorderPartCollisionPosition;
	}

	/**
	 * Returns the directions from which a collision occurs.
	 *
	 * @param Coordinate $_at The coordinate at which the border parts collide
	 * @param TextBorderPart $_inferiorBorderPart The inferior border part (The border part whose collision symbols will be ignored)
	 *
	 * @return String[] The collision direction names
	 */
	protected function getCollisionDirections(Coordinate $_at, TextBorderPart $_inferiorBorderPart): array
	{
		$collisionDirections = array();

		// Top left
		$topLeftCoordinate = new Coordinate(
			$_at->x() - 1,
			$_at->y() - 1
		);
		if ($_inferiorBorderPart->containsCoordinate($topLeftCoordinate)) $collisionDirections[] = "top-left";

		// Top
		$topCoordinate = new Coordinate(
			$_at->x(),
			$_at->y() - 1
		);
		if ($_inferiorBorderPart->containsCoordinate($topCoordinate)) $collisionDirections[] = "top";

		// Top right
		$topRightCoordinate = new Coordinate(
			$_at->x() + 1,
			$_at->y() - 1
		);
		if ($_inferiorBorderPart->containsCoordinate($topRightCoordinate)) $collisionDirections[] = "top-right";

		// Left
		$leftCoordinate = new Coordinate(
			$_at->x() - 1,
			$_at->y()
		);
		if ($_inferiorBorderPart->containsCoordinate($leftCoordinate)) $collisionDirections[] = "left";


		// Right
		$rightCoordinate = new Coordinate(
			$_at->x() + 1,
			$_at->y()
		);
		if ($_inferiorBorderPart->containsCoordinate($rightCoordinate)) $collisionDirections[] = "right";

		// Bottom Left
		$bottomLeftCoordinate = new Coordinate(
			$_at->x() - 1,
			$_at->y() + 1
		);
		if ($_inferiorBorderPart->containsCoordinate($bottomLeftCoordinate)) $collisionDirections[] = "bottom-left";

		// Bottom
		$bottomCoordinate = new Coordinate(
			$_at->x(),
			$_at->y() + 1
		);
		if ($_inferiorBorderPart->containsCoordinate($bottomCoordinate)) $collisionDirections[] = "bottom";

		// Bottom Right
		$bottomRightCoordinate = new Coordinate(
			$_at->x() + 1,
			$_at->y() + 1
		);
		if ($_inferiorBorderPart->containsCoordinate($bottomRightCoordinate)) $collisionDirections[] = "bottom-right";

		return $collisionDirections;
	}
}
