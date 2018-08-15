<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\Shapes;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

/**
 * Border shape for a border with no border parts.
 */
class NullBorderShape extends BaseBorderShape
{
	// Magic Methods

	/**
	 * NoBorderShape constructor.
	 */
	public function __construct()
	{
		parent::__construct(null);
	}


	// Class Methods

	/**
	 * Returns a empty list of border parts.
	 *
	 * @return BaseBorderPart[] The empty list of border parts
	 */
	public function getBorderParts()
	{
		return array();
	}
}
