<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPart;

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
	 * Returns an empty list of border parts.
	 *
	 * @return BorderPart[] The empty list of border parts
	 */
	public function getBorderParts()
	{
		return array();
	}

	/**
	 * Returns the maximum allowed Y-Coordinate for a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The maximum allowed Y-Coordinate
	 */
	public function getMaximumAllowedYCoordinate(int $_x): int
	{
		return 0;
	}

	/**
	 * Returns the maximum allowed X-Coordinate for a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The maximum allowed X-Coordinate
	 */
	public function getMaximumAllowedXCoordinate(int $_y): int
	{
		return 0;
	}
}
