<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\Border\BorderPart\Shapes;

use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

/**
 * Defines the shape of a border part.
 *
 * Call setParentBorderPart() before using other methods of this class
 * Call getRenderedBorderPart() to get the rendered parent border part
 */
abstract class BaseBorderPartShape
{
	// Attributes

    /**
     * The parent border part
     *
     * @var BaseBorderPart $parentBorderPart
     */
    protected $parentBorderPart;


    // Getters and setters

	/**
	 * Sets the parent border part.
	 *
	 * @param BaseBorderPart $_parentBorderPart The parent border part
	 */
	public function setParentBorderPart($_parentBorderPart)
	{
		$this->parentBorderPart = $_parentBorderPart;
	}


    // Class Methods

    /**
     * Creates and returns the rendered parent border part.
     *
     * @return mixed The rendered parent border part
     */
    abstract public function getRenderedBorderPart();
}
