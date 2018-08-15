<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\Shapes;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\Shapes\RectangleBorderShape;
use BoardRenderer\Image\Border\BorderPart\ImageBorderPart;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageHorizontalBorderPartShape;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageVerticalBorderPartShape;
use BoardRenderer\Image\Border\ImageBorder;
use GameOfLife\Coordinate;
use GameOfLife\Rectangle;

/**
 * Shape for rectangle image borders.
 */
class ImageRectangleBorderShape extends RectangleBorderShape
{
	// Attributes

	/**
	 * The parent border
	 *
	 * @var ImageBorder $parentBorder
	 */
	protected $parentBorder;


	// Magic Methods

	/**
	 * ImageRectangleBorderShape constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border
	 * @param Rectangle $_rectangle The rectangle of this border
	 */
	public function __construct(BaseBorder $_parentBorder, Rectangle $_rectangle)
	{
		parent::__construct($_parentBorder, $_rectangle);
	}


	// Class Methods

	/**
	 * Creates and returns the top border part.
	 *
	 * @return ImageBorderPart The top border part
	 */
	protected function getTopBorderPart(): ImageBorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x() - 1,
			$this->rectangle->topLeftCornerCoordinate()->y() - 1
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x(),
			$this->rectangle->topLeftCornerCoordinate()->y() - 1
		);

		return new ImageBorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageHorizontalBorderPartShape(),
			new BorderPartThickness(1, 15)
		);
	}

	/**
	 * Creates and returns the bottom border part.
	 *
	 * @return ImageBorderPart The bottom border part
	 */
	protected function getBottomBorderPart(): ImageBorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x() - 1,
			$this->rectangle->bottomRightCornerCoordinate()->y()
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x(),
			$this->rectangle->bottomRightCornerCoordinate()->y()
		);

		return new ImageBorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageHorizontalBorderPartShape(),
			new BorderPartThickness(1, 15)
		);
	}

	/**
	 * Creates and returns the left border part.
	 *
	 * @return ImageBorderPart The left border part
	 */
	protected function getLeftBorderPart(): ImageBorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x() - 1,
			$this->rectangle->topLeftCornerCoordinate()->y() - 1
		);
		$endsAt = new Coordinate(
			$this->rectangle->topLeftCornerCoordinate()->x() - 1,
			$this->rectangle->bottomRightCornerCoordinate()->y()
		);

		return new ImageBorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageVerticalBorderPartShape(),
			new BorderPartThickness(15, 1)
		);
	}

	/**
	 * Creates and returns the right border part.
	 *
	 * @return ImageBorderPart The right border part
	 */
	protected function getRightBorderPart(): ImageBorderPart
	{
		$startsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x(),
			$this->rectangle->topLeftCornerCoordinate()->y() - 1
		);
		$endsAt = new Coordinate(
			$this->rectangle->bottomRightCornerCoordinate()->x(),
			$this->rectangle->bottomRightCornerCoordinate()->y()
		);

		return new ImageBorderPart(
			$this->parentBorder,
			$startsAt,
			$endsAt,
			new ImageVerticalBorderPartShape(),
			new BorderPartThickness(15, 1)
		);
	}
}
