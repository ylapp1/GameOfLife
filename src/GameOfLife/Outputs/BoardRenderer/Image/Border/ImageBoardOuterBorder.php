<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border;

use BoardRenderer\Image\Border\Shapes\ImageRectangleBorderShape;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use GameOfLife\Rectangle;
use Output\Helpers\ImageColor;

/**
 * The outer border of the board for the ImageBoardRenderer.
 */
class ImageBoardOuterBorder extends ImageBorder
{
	// Magic Methods

	/**
	 * BoardOuterBorder constructor.
	 *
	 * @param Board $_board The board for which the outer border will be created
	 * @param ImageColor $_gridColor The color of the grid (and the borders)
	 * @param int $_fieldSize The height and width of each field
	 */
	public function __construct(Board $_board, ImageColor $_gridColor, int $_fieldSize)
	{
		$topLeftCornerCoordinate = new Coordinate(0, 0);
		$bottomRightCornerCoordinate = new Coordinate($_board->width() - 1, $_board->height() - 1);
		$rectangle = new Rectangle($topLeftCornerCoordinate, $bottomRightCornerCoordinate);

		parent::__construct(
			null,
			new ImageRectangleBorderShape(
				$this,
				$rectangle
			),
			$_gridColor,
			$_fieldSize
		);
	}
}
