<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer;

use BoardRenderer\Image\Border\ImageBoardOuterBorder;
use BoardRenderer\Image\CellImage\SmileyCellImage;
use BoardRenderer\Image\ImageBoardFieldRenderer;
use BoardRenderer\Image\ImageBorderGridBuilder;
use BoardRenderer\Image\ImageCanvas;
use BoardRenderer\Image\Utils\ImageColor;
use Simulator\Board;

/**
 * Renders boards as images.
 */
class ImageOutputBoardRenderer extends BaseBoardRenderer
{
	// Magic Methods

	/**
	 * ImageOutputBoardRenderer constructor.
	 *
	 * @param Board $_board The board
	 * @param Bool $_hasBackgroundGrid If true, the board will have a background grid that can be overwritten by borders
	 * @param int $_fieldSize The height and width of each field
	 * @param ImageColor $_backgroundColor The background color
	 * @param ImageColor $_foregroundColor The foreground color
	 * @param ImageColor $_gridColor The grid color
	 */
	public function __construct(Board $_board, Bool $_hasBackgroundGrid, int $_fieldSize, ImageColor $_backgroundColor, ImageColor $_foregroundColor, ImageColor $_gridColor)
	{
		$mainBorder = new ImageBoardOuterBorder($_board, $_gridColor);

		parent::__construct(
			$mainBorder,
			new ImageBorderGridBuilder($_board, $mainBorder, $_hasBackgroundGrid),
			$this->initializeBoardFieldRenderer($_fieldSize, $_foregroundColor),
			new ImageCanvas($_backgroundColor, true),
			$_fieldSize
		);
	}


	// Class Methods

	/**
	 * Creates and returns a board field renderer for this board renderer.
	 *
	 * @param int $_fieldSize The size of a field in pixels
	 * @param ImageColor $_foregroundColor The foreground color
	 *
	 * @return ImageBoardFieldRenderer The board field renderer
	 */
	private function initializeBoardFieldRenderer(int $_fieldSize, ImageColor $_foregroundColor): ImageBoardFieldRenderer
	{
		$headSize = $_fieldSize * 4/5;

		$smileyCellImage = new SmileyCellImage(
			$_foregroundColor,
			$headSize,
			$headSize
		);

		$boardFieldRenderer = new ImageBoardFieldRenderer($smileyCellImage->getImage(), null);

		return $boardFieldRenderer;
	}
}
