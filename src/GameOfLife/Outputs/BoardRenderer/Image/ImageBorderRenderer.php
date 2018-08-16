<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBorderRenderer;
use BoardRenderer\Image\Border\ImageBackgroundGridBorder;
use BoardRenderer\Image\Border\ImageBorder;
use GameOfLife\Board;
use Output\Helpers\ImageColor;

/**
 * Renders the borders as images.
 */
class ImageBorderRenderer extends BaseBorderRenderer
{
	/**
	 * ImageBorderRenderer constructor.
	 *
	 * @param Board $_board The board for which the border will be rendered
	 * @param ImageBorder $_border The border
	 * @param Bool $_hasBackgroundGrid If set to true there will be a background grid that can be overwritten by borders
	 * @param int $_fieldSize The height and width of a single field
	 * @param ImageColor $_backgroundColor The background color
	 */
	public function __construct(Board $_board, ImageBorder $_border, Bool $_hasBackgroundGrid = true, int $_fieldSize, ImageColor $_backgroundColor)
	{
		$borderGrid = new ImageBorderGrid(
			$_board,
			$_fieldSize,
			$_backgroundColor
		);

		if ($_hasBackgroundGrid)
		{
			$backgroundGridBorder = new ImageBackgroundGridBorder($_border, $_border->color(), $_fieldSize);
			$_border->addInnerBorder($backgroundGridBorder);
		}

		parent::__construct($_board, $_border, $borderGrid, $_hasBackgroundGrid);
	}
}
