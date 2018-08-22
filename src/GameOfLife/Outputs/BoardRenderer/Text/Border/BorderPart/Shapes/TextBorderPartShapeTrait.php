<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Text\Border\BorderPart\CollisionDirection;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextBorderPartCollisionPosition;
use GameOfLife\Coordinate;

/**
 * Additional methods for text border part shapes.
 */
trait TextBorderPartShapeTrait
{
	/**
	 * Returns the text border part collision position for a border part collision.
	 *
	 * @param Coordinate $_at The coordinate at which the border parts collide
	 * @param BorderPart $_with The colliding border part
	 *
	 * @return TextBorderPartCollisionPosition The text border part collision position
	 */
	protected function getTextBorderPartCollisionPosition(Coordinate $_at, $_with): TextBorderPartCollisionPosition
	{
		/** @var TextBorderPart $parentBorderPart */
		$parentBorderPart = $this->parentBorderPart;

		if ($parentBorderPart->isOuterBorderPart($_with)) $dominatingBorderPart = $_with;
		else $dominatingBorderPart = $parentBorderPart;

		$collisionDirection = new CollisionDirection($this->getCollisionDirections($_at, $_with));

		// TODO: Fix border symbol start and end positions instead of startsat and endsat
		// TODO: Fix collision position edge and first/last symbol
		if ($_at->equals($dominatingBorderPart->startsAt())) $collisionPosition = "start";
		elseif ($_at->equals($dominatingBorderPart->endsAt())) $collisionPosition = "end";
		else $collisionPosition = "center";

		$textBorderPartCollisionPosition = new TextBorderPartCollisionPosition(
			$_at->x(),
			$_at->y(),
			$collisionPosition,
			$collisionDirection
		);

		return $textBorderPartCollisionPosition;
	}

	/**
	 * Returns the directions from which a collision occurs.
	 *
	 * @param Coordinate $_at The coordinate at which the border parts collide
	 * @param BorderPart $_with The colliding border part
	 *
	 * @return String[] The collision direction names
	 */
	protected function getCollisionDirections(Coordinate $_at, $_with): array
	{
		$collisionDirections = array();

		// Top left
		$topLeftCoordinate = new Coordinate(
			$_at->x() - 1,
			$_at->y() - 1
		);
		if ($_with->containsCoordinate($topLeftCoordinate)) $collisionDirections[] = "top-left";

		// Top
		$topCoordinate = new Coordinate(
			$_at->x(),
			$_at->y() - 1
		);
		if ($_with->containsCoordinate($topCoordinate)) $collisionDirections[] = "top";

		// Top right
		$topRightCoordinate = new Coordinate(
			$_at->x() + 1,
			$_at->y() - 1
		);
		if ($_with->containsCoordinate($topRightCoordinate)) $collisionDirections[] = "top-right";

		// Left
		$leftCoordinate = new Coordinate(
			$_at->x() - 1,
			$_at->y()
		);
		if ($_with->containsCoordinate($leftCoordinate)) $collisionDirections[] = "left";


		// Right
		$rightCoordinate = new Coordinate(
			$_at->x() + 1,
			$_at->y()
		);
		if ($_with->containsCoordinate($rightCoordinate)) $collisionDirections[] = "right";

		// Bottom Left
		$bottomLeftCoordinate = new Coordinate(
			$_at->x() - 1,
			$_at->y() + 1
		);
		if ($_with->containsCoordinate($bottomLeftCoordinate)) $collisionDirections[] = "bottom-left";

		// Bottom
		$bottomCoordinate = new Coordinate(
			$_at->x(),
			$_at->y() + 1
		);
		if ($_with->containsCoordinate($bottomCoordinate)) $collisionDirections[] = "bottom";

		// Bottom Right
		$bottomRightCoordinate = new Coordinate(
			$_at->x() + 1,
			$_at->y() + 1
		);
		if ($_with->containsCoordinate($bottomRightCoordinate)) $collisionDirections[] = "bottom-right";

		return $collisionDirections;
	}
}
