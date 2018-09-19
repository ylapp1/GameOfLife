<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\Shapes\BaseBorderShape;

/**
 * Parent class for borders.
 *
 * Call getBorderParts() to get a list of border parts that form the border
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

	/**
	 * The border parts of this border
	 *
	 * @var BaseBorderPart[] $borderParts
	 */
	protected $borderParts;


	// Magic Methods

	/**
	 * BaseBorder constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border of this border
     * @param BaseBorderShape $_shape The shape of this border
	 */
	public function __construct(BaseBorder $_parentBorder = null, $_shape)
	{
		$this->innerBorders = array();
		$this->parentBorder = $_parentBorder;
		$this->shape = $_shape;
		$this->borderParts = array();
	}


	// Getters and Setters

	/**
	 * Returns the parent border of this border.
	 *
	 * @return BaseBorder|null The parent border of this border
	 */
	public function parentBorder()
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

	/**
	 * Returns the shape of this border.
	 *
	 * @return BaseBorderShape The shape of this border
	 */
	public function shape()
	{
		return $this->shape;
	}

	/**
	 * Returns the border parts of this border.
	 *
	 * @return BaseBorderPart[] The border parts
	 */
	public function borderParts(): array
	{
		return $this->borderParts;
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
	 * Returns all inner borders of this border.
	 *
	 * @return BaseBorder[] The list of inner borders
	 */
	public function getInnerBorders(): array
	{
		$borders = $this->innerBorders;
		foreach ($this->innerBorders as $innerBorder)
		{
			$innerBorders = $innerBorder->getInnerBorders();
			$borders = array_merge($borders, $innerBorders);
		}

		return $borders;
	}

	/**
	 * Resets the list of inner borders to an empty array.
	 */
	public function resetInnerBorders()
	{
		$this->innerBorders = array();
	}

	/**
	 * Returns all border parts of this border and its inner borders.
     *
     * @return BaseBorderPart[] The list of border parts
	 */
	public function getBorderParts()
	{
		if (! $this->borderParts)
		{
			$this->borderParts = $this->shape->getBorderParts();
		}
	    $allBorderParts = $this->borderParts;

	    foreach ($this->innerBorders as $innerBorder)
        {
            $innerBorderParts = $innerBorder->getBorderParts();
	        $allBorderParts = array_merge($allBorderParts, $innerBorderParts);
        }

        return $allBorderParts;
	}

	/**
	 * Returns whether this border contains a specific border.
	 *
	 * @param BaseBorder $_border The border
	 *
	 * @return Bool True if this border contains the border, false otherwise
	 */
	public function containsBorder($_border)
	{
		$containsBorder = false;
		$parentBorder = $_border->parentBorder();

		while ($parentBorder)
		{
			if ($parentBorder === $this)
			{
				$containsBorder = true;
				break;
			}

			$parentBorder = $parentBorder->parentBorder();
		}

		return $containsBorder;
	}
}
