<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Collision;

use BoardRenderer\Base\Border\BaseBorder;

class CollisionBorder extends BaseBorder
{
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

		foreach ($this->innerBorders as $innerBorder)
		{
			if ($innerBorder === $_border || $innerBorder->containsBorder($_border))
			{
				$containsBorder = true;
				break;
			}
		}

		return $containsBorder;
	}
}
