<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border;

use BoardRenderer\Image\Border\Shapes\ImageGridBorderShape;
use GameOfLife\Board;
use Output\Helpers\ImageColor;

/**
 * The background grid border.
 */
class ImageBackgroundGridBorder extends ImageBorder
{
	// Magic Methods

	/**
	 * BoardOuterBorder constructor.
	 *
	 * @param ImageBorder $_parentBorder The parent border
	 * @param Board $_board The board for which the outer border will be created
	 * @param ImageColor $_gridColor The color of the grid (and the borders)
	 * @param int $_fieldSize The height and width of each field
	 */
	public function __construct(ImageBorder $_parentBorder, Board $_board, ImageColor $_gridColor, int $_fieldSize)
	{
		parent::__construct(
			null,
			new ImageGridBorderShape(
				$this,
				$_board
			),
			$_gridColor,
			$_fieldSize
		);
	}
}
