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

/**
 * Creates border parts that form a specific shape.
 */
abstract class BaseBorderShape
{
    // Attributes

    /**
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
     * @return BaseBorderPart[] The list of border parts
     */
    abstract public function getBorderParts();

	/**
	 * Calculates and returns the total border width until a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The total column width of this border shape until that column
	 */
    abstract public function getBorderWidthInColumn(int $_x): int;

	/**
	 * Calculates and returns the total border height until a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The total border height of this border shape until that row
	 */
    abstract public function getBorderHeightInRow(int $_y): int;
}
