<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Base\Border\Shapes\RectangleBorderShape;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageHorizontalBorderPartShape;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageVerticalBorderPartShape;
use GameOfLife\Coordinate;

/**
 * Border shape for rectangle image borders.
 */
class ImageRectangleBorderShape extends RectangleBorderShape
{
	// Class Methods

	/**
	 * Creates and returns the top border part.
	 *
	 * @return BorderPart The top border part
	 */
	protected function getTopBorderPart(): BorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x(),
			$this->rectangle->topLeftCornerCoordinate()->y()
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->topLeftCornerCoordinate()->y()
		);

		return new BorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageHorizontalBorderPartShape(),
			$this->horizontalBorderPartsThickness
		);
	}

	/**
	 * Creates and returns the bottom border part.
	 *
	 * @return BorderPart The bottom border part
	 */
	protected function getBottomBorderPart(): BorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x(),
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);

		return new BorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageHorizontalBorderPartShape(),
			$this->horizontalBorderPartsThickness
		);
	}

	/**
	 * Creates and returns the left border part.
	 *
	 * @return BorderPart The left border part
	 */
	protected function getLeftBorderPart(): BorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x(),
			$this->rectangle->topLeftCornerCoordinate()->y()
		);
		$endsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x(),
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);

		return new BorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageVerticalBorderPartShape(),
			$this->verticalBorderPartsThickness
		);
	}

	/**
	 * Creates and returns the right border part.
	 *
	 * @return BorderPart The right border part
	 */
	protected function getRightBorderPart(): BorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->topLeftCornerCoordinate()->y()
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);

		return new BorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageVerticalBorderPartShape(),
			$this->verticalBorderPartsThickness
		);
	}
}
