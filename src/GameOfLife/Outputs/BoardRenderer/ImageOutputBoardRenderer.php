<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer;

use GameOfLife\Board;
use BoardRenderer\Base\BaseBoardRenderer;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Image\CellImage\SmileyCellImage;
use BoardRenderer\Image\ImageBoardFieldRenderer;
use BoardRenderer\Image\ImageBorderRenderer;
use BoardRenderer\Image\ImageCanvas;
use Output\Helpers\ImageColor;

/**
 * Renders boards as resources.
 */
class ImageOutputBoardRenderer extends BaseBoardRenderer
{
	// Magic Methods

	/**
	 * ImageOutputBoardRenderer constructor.
	 *
	 * @param Board $_board The board
	 * @param BaseBorder $_border The border
	 * @param Bool $_hasBackgroundGrid If true, the board will have a background grid that can be overwritten by borders
	 * @param int $_fieldSize The height and width of each field
	 * @param ImageColor $_backgroundColor The background color
	 * @param ImageColor $_foregroundColor The foreground color
	 * @param ImageColor $_gridColor The grid color
	 */
	public function __construct(Board $_board, BaseBorder $_border = null, Bool $_hasBackgroundGrid, int $_fieldSize, ImageColor $_backgroundColor, ImageColor $_foregroundColor, ImageColor $_gridColor)
	{
		parent::__construct(
			$_border,
			new ImageBorderRenderer($_hasBackgroundGrid, $_board, $_fieldSize, $_backgroundColor, $_gridColor),
			$this->initializeBoardFieldRenderer($_fieldSize, $_backgroundColor, $_foregroundColor),
			new ImageCanvas($_fieldSize)
		);
	}


	// Class Methods

	/**
	 * Creates and returns a board field renderer for this board renderer.
	 *
	 * @param int $_fieldSize The size of a field in pixels
	 * @param ImageColor $_backgroundColor The background color
	 * @param ImageColor $_foreGroundColor The foreground color
	 *
	 * @return ImageBoardFieldRenderer The board field renderer
	 */
	private function initializeBoardFieldRenderer(int $_fieldSize, ImageColor $_backgroundColor, ImageColor $_foreGroundColor): ImageBoardFieldRenderer
	{
		$headSize = $_fieldSize * 4/5;

		$smileyCellImage = new SmileyCellImage(
			$_backgroundColor,
			$_foreGroundColor,
			$headSize,
			$headSize
		);

		$boardFieldRenderer = new ImageBoardFieldRenderer(
			$_fieldSize,
			$smileyCellImage->getImage(),
			null
		);

		return $boardFieldRenderer;
	}
}
