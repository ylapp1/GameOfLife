<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\Shapes;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use GameOfLife\Coordinate;

/**
 * Base class for background grid border shapes.
 */
abstract class BaseGridBorderShape extends BaseBorderShape
{
	// Attributes

	/**
	 * Defines the thickness for horizontal border parts of this border
	 *
	 * @var BorderPartThickness $horizontalBorderPartsThickness
	 */
	protected $horizontalBorderPartsThickness;

	/**
	 * Defines the thickness for vertical border parts of this border
	 *
	 * @var BorderPartThickness $verticalBorderPartsThickness
	 */
	protected $verticalBorderPartsThickness;


	// Magic Methods

	/**
	 * BaseGridBorderShape constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border of this border shape
	 * @param BorderPartThickness $_horizontalThickness The thickness for horizontal border parts of this border
	 * @param BorderPartThickness $_verticalThickness The thickness for vertical border parts of this border
	 */
	public function __construct(BaseBorder $_parentBorder, BorderPartThickness $_horizontalThickness, BorderPartThickness $_verticalThickness)
	{
		parent::__construct($_parentBorder);
		$this->horizontalBorderPartsThickness = $_horizontalThickness;
		$this->verticalBorderPartsThickness = $_verticalThickness;
	}


	// Class Methods

	/**
	 * Returns the border parts for the background grid
	 *
	 * @return BorderPart[] The border parts for the background grid
	 */
	public function getBorderParts()
	{
		$parentBorderShape = $this->parentBorder->parentBorder()->shape();
		if (! $parentBorderShape instanceof RectangleBorderShape)
		{
			// TODO: Make background grid work with all border shapes
			return array();
		}

		$backgroundGridBorderParts = array();

		$startsAt = $parentBorderShape->rectangle()->topLeftCornerCoordinate();
		$endsAt = $parentBorderShape->rectangle()->bottomRightCornerCoordinate();

		$width = $endsAt->x() - $startsAt->x() + 1;
		$height = $endsAt->y() - $startsAt->y() + 1;

		// Add horizontal border parts
		for ($y = $startsAt->y() + 1; $y <= $endsAt->y(); $y++)
		{
			$borderPart = $this->getHorizontalBackgroundGridBorderPart(
				new Coordinate(0, $y),
				new Coordinate($width, $y)
			);
			$backgroundGridBorderParts[] = $borderPart;
		}

		// Add vertical border parts
		for ($x = $startsAt->x() + 1; $x <= $endsAt->x(); $x++)
		{
			$borderPart = $this->getVerticalBackgroundGridBorderPart(
				new Coordinate($x, 0),
				new Coordinate($x, $height)
			);
			$backgroundGridBorderParts[] = $borderPart;
		}

		return $backgroundGridBorderParts;
	}

	/**
	 * Returns a horizontal border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 *
	 * @return BorderPart The horizontal border part
	 */
	abstract protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt);

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 *
	 * @return BorderPart The vertical border part
	 */
	abstract protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt);

	/**
	 * Returns the maximum allowed Y-Coordinate for a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The maximum allowed Y-Coordinate
	 */
	public function getMaximumAllowedYCoordinate(int $_x): int
	{
		return $this->parentBorder->parentBorder()->shape()->getMaximumAllowedYCoordinate($_x);
	}

	/**
	 * Returns the maximum allowed X-Coordinate for a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The maximum allowed X-Coordinate
	 */
	public function getMaximumAllowedXCoordinate(int $_y): int
	{
		return $this->parentBorder->parentBorder()->shape()->getMaximumAllowedXCoordinate($_y);
	}
}
