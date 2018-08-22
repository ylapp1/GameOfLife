<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\Border\TextBackgroundGridBorder;
use GameOfLife\Board;
use BoardRenderer\Base\BaseBorderRenderer;

/**
 * Renders a border and its inner borders and adds them to a canvas.
 */
class TextBorderRenderer extends BaseBorderRenderer
{
	// Magic Methods

	/**
	 * ImageBorderRenderer constructor.
	 *
	 * @param Board $_board The board for which the border will be rendered
	 * @param BaseBorder $_border The border
	 * @param Bool $_hasBackgroundGrid If set to true there will be a background grid that can be overwritten by borders
	 */
	public function __construct(Board $_board, BaseBorder $_border, Bool $_hasBackgroundGrid = false)
	{
		$borderGrid = new TextBorderGrid($_board);

		if ($_hasBackgroundGrid)
		{
			$backgroundGridBorder = new TextBackgroundGridBorder($_border);
			$_border->addInnerBorder($backgroundGridBorder);
		}

		parent::__construct($_board, $_border, $borderGrid, $_hasBackgroundGrid);
	}
}
