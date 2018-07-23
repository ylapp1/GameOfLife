<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use Output\BoardRenderer\Base\Border\BaseBorder;

/**
 * Renders a border and its inner borders and adds them to a canvas.
 */
abstract class BaseBorderRenderer
{
    // Class Methods

	/**
	 * Renders a border and its inner borders and adds them to a canvas.
     *
     * @param BaseBorder $_border The border
     * @param BaseCanvas $_canvas The canvas
	 */
	public function renderBorder($_border, $_canvas)
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

            $borderPart->addToCanvas($_canvas);
        }

        // TODO: Cache rendered border parts
    }
}
