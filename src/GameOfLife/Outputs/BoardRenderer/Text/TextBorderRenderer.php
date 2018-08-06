<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;

use Output\BoardRenderer\Base\BaseBorderRenderer;
use Output\BoardRenderer\Base\Border\BaseBorder;

/**
 * Renders a border and its inner borders and adds them to a canvas.
 */
class TextBorderRenderer extends BaseBorderRenderer
{
	/**
	 * Renders a border and adds it to a canvas.
	 *
	 * @param BaseBorder $_border The border
	 * @param TextCanvas $_canvas The canvas
	 */
	public function renderBorder($_border, $_canvas)
	{
		parent::renderBorder($_border, $_canvas);
	}
}
