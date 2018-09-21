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
     * @return BaseBorderPart[] The list of border parts
     */
    abstract public function getBorderParts();

	/**
	 * Calculates and returns the start Y-Coordinate in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The start Y-Coordinate
	 */
    abstract public function getStartY(int $_x);

	/**
	 * Calculates and returns the end Y-Coordinate in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The end Y-Coordinate
	 */
    abstract public function getEndY(int $_x);

	/**
	 * Calculates and returns the start X-Coordinate in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The start X-Coordinate
	 */
    abstract public function getStartX(int $_y);

	/**
	 * Calculates and returns the end X-Coordinate in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The start X-Coordinate
	 */
    abstract public function getEndX(int $_y);

	/**
	 * Returns the row ids that are covered by this border shape.
	 *
	 * @return int[] The list of row ids
	 */
    abstract public function getRowIds(): array;

	/**
	 * Returns the column ids that are covered by this border shape.
	 *
	 * @return int[] The list of column ids
	 */
    abstract public function getColumnIds(): array;
}
