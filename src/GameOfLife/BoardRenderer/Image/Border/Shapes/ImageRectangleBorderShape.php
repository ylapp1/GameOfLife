<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\Shapes;

use BoardRenderer\Base\Border\Shapes\RectangleBorderShape;
use BoardRenderer\Image\Border\BorderPart\ImageHorizontalBorderPart;
use BoardRenderer\Image\Border\BorderPart\ImageVerticalBorderPart;
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
	 * @return ImageHorizontalBorderPart The top border part
	 */
	protected function getTopBorderPart(): ImageHorizontalBorderPart
	{
		$startsAt = clone $this->rectangle->topLeftCornerCoordinate();
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->topLeftCornerCoordinate()->y()
		);

		return new ImageHorizontalBorderPart(
			$this,
			$startsAt,
			$endsAt,
			$this->horizontalBorderPartsThickness
		);
	}

	/**
	 * Creates and returns the bottom border part.
	 *
	 * @return ImageHorizontalBorderPart The bottom border part
	 */
	protected function getBottomBorderPart(): ImageHorizontalBorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x(),
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);

		return new ImageHorizontalBorderPart(
			$this,
			$startsAt,
			$endsAt,
			$this->horizontalBorderPartsThickness
		);
	}

	/**
	 * Creates and returns the left border part.
	 *
	 * @return ImageVerticalBorderPart The left border part
	 */
	protected function getLeftBorderPart(): ImageVerticalBorderPart
	{
		$startsAt = clone $this->rectangle->topLeftCornerCoordinate();
		$endsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x(),
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);

		return new ImageVerticalBorderPart(
			$this,
			$startsAt,
			$endsAt,
			$this->verticalBorderPartsThickness
		);
	}

	/**
	 * Creates and returns the right border part.
	 *
	 * @return ImageVerticalBorderPart The right border part
	 */
	protected function getRightBorderPart(): ImageVerticalBorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->topLeftCornerCoordinate()->y()
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
			$this->rectangle->bottomRightCornerCoordinate()->y() + 1
		);

		return new ImageVerticalBorderPart(
			$this,
			$startsAt,
			$endsAt,
			$this->verticalBorderPartsThickness
		);
	}
}
