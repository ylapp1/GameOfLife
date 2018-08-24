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

/**
 * Creates and returns border parts that form a specific shape.
 */
abstract class BaseBorderShape
{
    // Attributes

    /**
     * The parent border
     *
     * @var BaseBorder $parentBorder
     */
    protected $parentBorder;


    // Magic Methods

    /**
     * BaseBorderShape constructor.
     *
     * @param BaseBorder $_parentBorder The parent border
     */
    protected function __construct($_parentBorder)
    {
        $this->parentBorder = $_parentBorder;
    }


    // Getters and Setters

    /**
     * Returns the parent border of this border shape.
     *
     * @return BaseBorder The parent border of this border shape
     */
    public function parentBorder()
    {
        return $this->parentBorder;
    }


    // Class Methods

    /**
     * Returns all border parts of this border shape.
     *
     * @return BorderPart[] The list of border parts
     */
    abstract public function getBorderParts();

	/**
	 * Returns the maximum allowed Y-Coordinate for a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The maximum allowed Y-Coordinate
	 */
    abstract public function getMaximumAllowedYCoordinate(int $_x): int;

	/**
	 * Returns the maximum allowed X-Coordinate for a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The maximum allowed X-Coordinate
	 */
    abstract public function getMaximumAllowedXCoordinate(int $_y): int;
}
