<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Collision;

use Output\BoardRenderer\Base\BaseBorderRenderer;
use Output\BoardRenderer\Base\Border\BaseBorder;

class CollisionBorderRenderer extends BaseBorderRenderer
{
	/**
	 * Renders a border and its inner borders and adds them to a canvas.
	 *
	 * @param BaseBorder $_border The border
	 */
	public function renderBorder($_border)
	{
		$fetchedBorderParts = array();

		// Fetch the border parts
		foreach ($_border->getBorderParts() as $borderPart)
		{
			foreach ($fetchedBorderParts as $fetchedBorderPart)
			{
				$borderPart->checkCollisionWith($fetchedBorderPart);
			}

			$fetchedBorderParts[] = $borderPart;
		}
	}
}
