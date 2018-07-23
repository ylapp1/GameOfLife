<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

/**
 * Creates border parts that form a rectangle.
 */
abstract class RectangleBorderShape extends BaseBorderShape
{
	// Attributes

    // TODO: Get rid of code duplication in selection area and this

	/**
	 * The top left corner coordinate of this border shape
	 *
	 * @var Coordinate $topLeftCornerCoordinate
	 */
	protected $topLeftCornerCoordinate;

	/**
	 * The bottom right corner coordinate of this border shape
	 *
	 * @var Coordinate $bottomRightCornerCoordinate
	 */
    protected $bottomRightCornerCoordinate;


	// Magic Methods

	/**
	 * RectangleBorderShape constructor.
	 *
     * @param BaseBorder $_parentBorder The parent border of this border shape
	 * @param Coordinate $_topLeftCornerCoordinate The top left corner coordinate of this border shape
	 * @param Coordinate $_bottomRightCornerCoordinate The bottom right corner coordinate of this border shape
	 */
	protected function __construct($_parentBorder, Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate)
	{
	    parent::__construct($_parentBorder);
		$this->topLeftCornerCoordinate = $_topLeftCornerCoordinate;
		$this->bottomRightCornerCoordinate = $_bottomRightCornerCoordinate;
	}


	// Class Methods

	/**
	 * Returns all border parts of this border shape.
     *
     * @return BaseBorderPart[] The list of border parts
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
	 * @return BaseBorderPart The top border part of this border shape
	 */
	abstract protected function getTopBorderPart();

	/**
	 * Generates and returns the bottom border part of this border shape.
     * This must return a border part with a horizontal shape.
     *
	 * @return BaseBorderPart The bottom border part of this border shape
	 */
	abstract protected function getBottomBorderPart();

	/**
	 * Generates and returns the left border part of this border shape.
     * This must return a border part with a vertical shape.
     *
	 * @return BaseBorderPart The left border part of this border shape
	 */
	abstract protected function getLeftBorderPart();

	/**
	 * Generates and returns the right border part of this border shape.
     * This must return a border part with a vertical shape.
     *
	 * @return BaseBorderPart The right border part of this border shape
	 */
	abstract protected function getRightBorderPart();
}
