<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use GameOfLife\Rectangle;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPart;

/**
 * Creates border parts that form a rectangle.
 */
abstract class RectangleBorderShape extends BaseBorderShape
{
	// Attributes

	/**
	 * The rectangle
	 *
	 * @var Rectangle $rectangle
	 */
	protected $rectangle;

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
	 * RectangleBorderShape constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border of this border shape
	 * @param Rectangle $_rectangle The rectangle
	 * @param BorderPartThickness $_horizontalThickness The thickness for horizontal border parts of this border
	 * @param BorderPartThickness $_verticalThickness The thickness for vertical border parts of this border
	 */
	public function __construct($_parentBorder, Rectangle $_rectangle, BorderPartThickness $_horizontalThickness, BorderPartThickness $_verticalThickness)
	{
	    parent::__construct($_parentBorder);
		$this->rectangle = $_rectangle;
		$this->horizontalBorderPartsThickness = $_horizontalThickness;
		$this->verticalBorderPartsThickness = $_verticalThickness;
	}


	// Class Methods

	/**
	 * Returns all border parts of this border shape.
     *
     * @return BorderPart[] The list of border parts
	 */
	public function getBorderParts()
	{
	    return array(
	        $this->getTopBorderPart(),
            $this->getBottomBorderPart(),
            $this->getLeftBorderPart(),
            $this->getRightBorderPart()
        );
	}

	/**
	 * Generates and returns the top border part of this border shape.
     * This must return a border part with a horizontal shape.
	 *
	 * @return BorderPart The top border part of this border shape
	 */
	abstract protected function getTopBorderPart();

	/**
	 * Generates and returns the bottom border part of this border shape.
     * This must return a border part with a horizontal shape.
     *
	 * @return BorderPart The bottom border part of this border shape
	 */
	abstract protected function getBottomBorderPart();

	/**
	 * Generates and returns the left border part of this border shape.
     * This must return a border part with a vertical shape.
     *
	 * @return BorderPart The left border part of this border shape
	 */
	abstract protected function getLeftBorderPart();

	/**
	 * Generates and returns the right border part of this border shape.
     * This must return a border part with a vertical shape.
     *
	 * @return BorderPart The right border part of this border shape
	 */
	abstract protected function getRightBorderPart();

	/**
	 * Calculates and returns the start Y-Coordinate in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The start Y-Coordinate
	 */
	public function getStartY(int $_x)
	{
		if ($this->rectangle->topLeftCornerCoordinate()->x() <= $_x && $this->rectangle->bottomRightCornerCoordinate()->x() + 1 >= $_x)
		{
			return $this->rectangle->topLeftCornerCoordinate()->y();
		}
		else return null;
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
		if ($this->rectangle->topLeftCornerCoordinate()->x() <= $_x && $this->rectangle->bottomRightCornerCoordinate()->x() + 1 >= $_x)
		{
			return $this->rectangle->bottomRightCornerCoordinate()->y() + 1;
		}
		else return null;
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
		if ($this->rectangle->topLeftCornerCoordinate()->y() <= $_y && $this->rectangle->bottomRightCornerCoordinate()->y() + 1 >= $_y)
		{
			return $this->rectangle->topLeftCornerCoordinate()->x();
		}
		else return null;
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
		if ($this->rectangle->topLeftCornerCoordinate()->y() <= $_y && $this->rectangle->bottomRightCornerCoordinate()->y() + 1 >= $_y)
		{
			return $this->rectangle->bottomRightCornerCoordinate()->x() + 1;
		}
		else return null;
	}

	/**
	 * Returns the row ids that are covered by this border shape.
	 *
	 * @return int[] The list of row ids
	 */
	public function getRowIds(): array
	{
		$rowIds = array();
		for ($y = $this->rectangle->topLeftCornerCoordinate()->y(); $y <= $this->rectangle->bottomRightCornerCoordinate()->y(); $y++)
		{
			$rowIds[] = $y;
		}

		return $rowIds;
	}

	/**
	 * Returns the column ids that are covered by this border shape.
	 *
	 * @return int[] The list of column ids
	 */
	public function getColumnIds(): array
	{
		$columnIds = array();
		for ($x = $this->rectangle->topLeftCornerCoordinate()->x(); $x <= $this->rectangle->bottomRightCornerCoordinate()->x(); $x++)
		{
			$columnIds[] = $x;
		}

		return $columnIds;
	}
}
