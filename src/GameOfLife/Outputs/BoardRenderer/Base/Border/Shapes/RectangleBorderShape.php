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
use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

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
	protected function __construct($_parentBorder, Rectangle $_rectangle)
	{
	    parent::__construct($_parentBorder);
		$this->rectangle = $_rectangle;
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
