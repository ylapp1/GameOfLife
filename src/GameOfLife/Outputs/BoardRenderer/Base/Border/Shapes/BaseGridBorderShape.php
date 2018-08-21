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
	 * Defines the thickness of horizontal parts of this border
	 *
	 * @var BorderPartThickness $horizontalThickness
	 */
	protected $horizontalThickness;

	/**
	 * Defines the thickness of vertical parts of this border
	 *
	 * @var BorderPartThickness $verticalThickness
	 */
	protected $verticalThickness;


	// Magic Methods

	/**
	 * BaseGridBorderShape constructor.
	 *
	 * @param BaseBorder $_parentBorder
	 * @param BorderPartThickness $_horizontalThickness
	 * @param BorderPartThickness $_verticalThickness
	 */
	public function __construct(BaseBorder $_parentBorder, BorderPartThickness $_horizontalThickness, BorderPartThickness $_verticalThickness)
	{
		parent::__construct($_parentBorder);
		$this->horizontalThickness = $_horizontalThickness;
		$this->verticalThickness = $_verticalThickness;
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
			echo "No is not";

			// TODO: Currently the background grid can only be applied to rectangle border shapes
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
				new Coordinate($width, $y),
				$this->parentBorder
			);
			$backgroundGridBorderParts[] = $borderPart;
		}

		// Add vertical border parts
		for ($x = $startsAt->x() + 1; $x <= $endsAt->x(); $x++)
		{
			$borderPart = $this->getVerticalBackgroundGridBorderPart(
				new Coordinate($x, 0),
				new Coordinate($x, $height),
				$this->parentBorder
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
	 * @param BaseBorder $_parentBorder The main border
	 *
	 * @return BorderPart The horizontal border part
	 */
	abstract protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder);

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param BaseBorder $_parentBorder The main border
	 *
	 * @return BorderPart The vertical border part
	 */
	abstract protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder);


	public function getBorderWidthInColumn(int $_x): int
	{
		$parentBorderShape = $this->parentBorder->parentBorder()->shape();
		if (! $parentBorderShape instanceof RectangleBorderShape)
		{
			// TODO: Currently the background grid can only be applied to rectangle border shapes
			return 0;
		}

		$startsAt = $parentBorderShape->rectangle()->topLeftCornerCoordinate();
		$endsAt = $parentBorderShape->rectangle()->bottomRightCornerCoordinate();

		// TODO: Fix fixed number ...
		if ($_x > $startsAt->x() && $_x <= $endsAt->x()) return 1;
		else return 0;
	}

	public function getBorderHeightInRow(int $_y): int
	{
		$parentBorderShape = $this->parentBorder->parentBorder()->shape();
		if (! $parentBorderShape instanceof RectangleBorderShape)
		{
			// TODO: Currently the background grid can only be applied to rectangle border shapes
			return 0;
		}

		$startsAt = $parentBorderShape->rectangle()->topLeftCornerCoordinate();
		$endsAt = $parentBorderShape->rectangle()->bottomRightCornerCoordinate();

		// TODO: Fix fixed number ...
		if ($_y > $startsAt->y() && $_y <= $endsAt->y()) return 1;
		else return 0;
	}

	public function getMaximumAllowedYCoordinate(int $_y): int
	{
		return $this->parentBorder->parentBorder()->shape()->getMaximumAllowedYCoordinate($_y);
	}

	public function getMaximumAllowedXCoordinate(int $_x): int
	{
		return $this->parentBorder->parentBorder()->shape()->getMaximumAllowedXCoordinate($_x);
	}
}
