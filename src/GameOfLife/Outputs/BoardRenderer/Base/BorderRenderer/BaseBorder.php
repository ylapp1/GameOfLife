<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\BorderPartBuilder;

use Output\BoardPrinter\OutputBoard\BorderPartBuilder\InnerBorderPartBuilder\BaseInnerBorder;
use Output\BoardRenderer\Base\BaseBorderRenderer;
use Output\BoardRenderer\Base\BorderShapes\BaseBorderShape;

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
	 * @var BaseInnerBorder[] $innerBorders
	 */
	protected $innerBorders;

	/**
	 * The parent border of this border
	 *
	 * @var BaseBorder $parentBorder
	 */
	private $parentBorder;

	/**
	 * The border shape of this border
	 *
	 * @var BaseBorderShape $borderShape
	 */
	private $borderShape;


	// Magic Methods

	/**
	 * BaseBorder constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border of this border
	 */
	protected function __construct(BaseBorder $_parentBorder = null)
	{
		$this->innerBorders = array();
		$this->parentBorder = $_parentBorder;
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
	 * @param BaseBorderRenderer $_borderRenderer The output board
	 */
	public function addBorderPartsToBorderRenderer(BaseBorderRenderer $_borderRenderer)
	{
		$this->borderShape->addBorderPartsToBorderRenderer($_borderRenderer);

		foreach ($this->innerBorders as $innerBorder)
		{
			$innerBorder->addBorderPartsToBorderRenderer($_borderRenderer);
		}
	}
}
