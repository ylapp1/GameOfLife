<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\Shapes;

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


	// Magic Methods

	/**
	 * RectangleBorderShape constructor.
	 *
     * @param BaseBorder $_parentBorder The parent border of this border shape
	 * @param Rectangle $_rectangle The rectangle
	 */
	public function __construct($_parentBorder, Rectangle $_rectangle)
	{
	    parent::__construct($_parentBorder);
		$this->rectangle = $_rectangle;
	}


	// Getters and Setters

	public function rectangle(): Rectangle
	{
		return $this->rectangle;
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
	 * Calculates and returns the total border width until a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The total column width of this border shape until that column
	 */
	public function getBorderWidthInColumn(int $_x): int
	{
		$borderWidth = 0;

		// TODO: Fix the way in which thickness is fetched (instead add border shape thickness attribute)

		if ($_x == $this->rectangle->topLeftCornerCoordinate()->x())
		{
			$borderWidth += $this->getLeftBorderPart()->thickness()->width();
		}
		if ($_x == $this->rectangle->bottomRightCornerCoordinate()->x() + 1)
		{
			$borderWidth += $this->getRightBorderPart()->thickness()->width();
		}

		return $borderWidth;
	}

	/**
	 * Calculates and returns the total border height until a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The total border height of this border shape until that row
	 */
	public function getBorderHeightInRow(int $_y): int
	{
		$borderHeight = 0;

		// TODO: Fix the way in which thickness is fetched (instead add border shape thickness attribute)

		if ($_y == $this->rectangle->topLeftCornerCoordinate()->y())
		{
			$borderHeight += $this->getTopBorderPart()->thickness()->height();
		}
		if ($_y == $this->rectangle->bottomRightCornerCoordinate()->y() + 1)
		{
			$borderHeight += $this->getBottomBorderPart()->thickness()->height();
		}

		return $borderHeight;
	}
}
