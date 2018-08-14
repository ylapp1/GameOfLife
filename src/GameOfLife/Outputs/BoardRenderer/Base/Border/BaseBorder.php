<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border;

use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use Output\BoardRenderer\Base\Border\Shapes\BaseBorderShape;

/**
 * Parent class for border printers.
 *
 * Call getBorderTopString() and getBorderBottomString() to get the top/bottom border strings
 * Call addBordersToRowString() to add the left/right borders to a single row
 */
abstract class BaseBorder
{
	// Attributes

	/**
	 * The list of inner borders of this border
	 *
	 * @var BaseBorder[] $innerBorders
	 */
	protected $innerBorders;

	/**
	 * The parent border of this border
	 *
	 * @var BaseBorder $parentBorder
	 */
	protected $parentBorder;

	/**
	 * The border shape of this border
	 *
	 * @var BaseBorderShape $shape
	 */
	protected $shape;


	// Magic Methods

	/**
	 * BaseBorder constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border of this border
     * @param BaseBorderShape $_shape The shape of this border
	 */
	protected function __construct(BaseBorder $_parentBorder = null, $_shape)
	{
		$this->innerBorders = array();
		$this->parentBorder = $_parentBorder;
		$this->shape = $_shape;
	}


	// Getters and Setters

	/**
	 * Returns the parent border of this border.
	 *
	 * @return BaseBorder The parent border of this border
	 */
	public function parentBorder(): BaseBorder
	{
		return $this->parentBorder;
	}

	/**
	 * Sets the parent border of this border.
	 *
	 * @param BaseBorder $_parentBorder The parent border
	 */
	public function setParentBorder(BaseBorder $_parentBorder)
	{
		$this->parentBorder = $_parentBorder;
	}


	// Class Methods

	/**
	 * Adds an inner border to this border.
	 *
	 * @param BaseBorder $_innerBorder The inner border
	 */
	public function addInnerBorder(BaseBorder $_innerBorder)
	{
		$this->innerBorders[] = $_innerBorder;
		$_innerBorder->setParentBorder($this);
	}

	/**
	 * Resets the list of inner borders to an empty array.
	 */
	public function resetInnerBorders()
	{
		$this->innerBorders = array();
	}

	/**
	 * Adds all borders of this border part builder to an output board.
     *
     * @return BaseBorderPart[] The list of border parts
	 */
	public function getBorderParts()
	{
	    $borderParts = $this->shape->getBorderParts();

	    foreach ($this->innerBorders as $innerBorder)
        {
            $innerBorderParts = $innerBorder->getBorderParts();
            $borderParts = array_merge($borderParts, $innerBorderParts);
        }

        return $borderParts;
	}
}
