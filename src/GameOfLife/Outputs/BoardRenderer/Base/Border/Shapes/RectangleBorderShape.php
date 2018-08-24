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


	// Getters and Setters

	// TODO: This can be removed when border grid shape is independent of this shape
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
	 * Returns the maximum allowed Y-Coordinate for a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The maximum allowed Y-Coordinate
	 */
	public function getMaximumAllowedYCoordinate(int $_x): int
	{
		return $this->rectangle->bottomRightCornerCoordinate()->y();
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
		return $this->rectangle->bottomRightCornerCoordinate()->x();
	}
}
