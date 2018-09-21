<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\Shapes;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
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
	 * @return BaseBorderPart[] The border parts for the background grid
	 */
	public function getBorderParts()
	{
		$parentBorderShape = $this->parentBorder->parentBorder()->shape();
		$backgroundGridBorderParts = array();


		// Add horizontal border parts
		$rowIds = $parentBorderShape->getRowIds();
		unset($rowIds[0]);

		foreach ($rowIds as $y)
		{
			$startX = $parentBorderShape->getStartX($y);
			$endX = $parentBorderShape->getEndX($y);

			$borderPart = $this->getHorizontalBackgroundGridBorderPart(
				new Coordinate($startX, $y),
				new Coordinate($endX, $y)
			);
			$backgroundGridBorderParts[] = $borderPart;
		}


		// Add vertical border parts
		$columnIds = $parentBorderShape->getColumnIds();
		unset($columnIds[0]);

		foreach ($columnIds as $x)
		{
			$startY = $parentBorderShape->getStartY($x);
			$endY = $parentBorderShape->getEndY($x);

			$borderPart = $this->getVerticalBackgroundGridBorderPart(
				new Coordinate($x, $startY),
				new Coordinate($x, $endY)
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
	 * @return BaseBorderPart The horizontal border part
	 */
	abstract protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt);

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 *
	 * @return BaseBorderPart The vertical border part
	 */
	abstract protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt);

	/**
	 * Calculates and returns the start Y-Coordinate in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The start Y-Coordinate
	 */
	public function getStartY(int $_x)
	{
		return $this->parentBorder->parentBorder()->shape()->getStartY($_x);
	}

	/**
	 * Calculates and returns the end Y-Coordinate in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The end Y-Coordinate
	 */
	public function getEndY(int $_x)
	{
		return $this->parentBorder->parentBorder()->shape()->getEndY($_x);
	}

	/**
	 * Calculates and returns the start X-Coordinate in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The start X-Coordinate
	 */
	public function getStartX(int $_y)
	{
		return $this->parentBorder->parentBorder()->shape()->getStartX($_y);
	}

	/**
	 * Calculates and returns the end X-Coordinate in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The start X-Coordinate
	 */
	public function getEndX(int $_y)
	{
		return $this->parentBorder->parentBorder()->shape()->getEndX($_y);
	}

	/**
	 * Returns the row ids that are covered by this border shape.
	 *
	 * @return int[] The list of row ids
	 */
	public function getRowIds(): array
	{
		return $this->parentBorder->parentBorder()->shape()->getRowIds();
	}

	/**
	 * Returns the column ids that are covered by this border shape.
	 *
	 * @return int[] The list of column ids
	 */
	public function getColumnIds(): array
	{
		return $this->parentBorder->parentBorder()->shape()->getColumnIds();
	}
}
