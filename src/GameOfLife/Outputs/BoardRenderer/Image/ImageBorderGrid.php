<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBorderGrid;
use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;
use BoardRenderer\Base\BorderPositionsGrid;
use BoardRenderer\Image\Utils\ImageColor;
use BoardRenderer\Image\Utils\TransparentImageUtils;
use GameOfLife\Board;

/**
 * Rendered border grid for the image board renderer.
 */
class ImageBorderGrid extends BaseBorderGrid
{
	// Attributes

	/**
	 * The board for which the border grid is created
	 *
	 * @var Board $board
	 */
	private $board;

	/**
	 * The transparent image utils
	 *
	 * @var TransparentImageUtils $transparentImageUtils
	 */
	private $transparentImageUtils;

	/**
	 * The list of border colors
	 *
	 * @var ImageColor[] $borderColors
	 */
	private $borderColors;


	// Magic Methods

	/**
	 * ImageBorderGrid constructor.
	 *
	 * @param Board $_board The board for which the border grid is created
	 * @param ImageColor[] $_borderColors The list of border colors
	 */
	public function __construct(Board $_board, array $_borderColors)
	{
		parent::__construct(new BorderPositionsGrid($_board));
		$this->board = $_board;
		$this->transparentImageUtils = new TransparentImageUtils();
		$this->borderColors = $_borderColors;
	}


	// Class Methods

	/**
	 * Creates and returns the rendered border grid.
	 *
	 * @param RenderedBorderPart[] $_renderedBorderParts
	 * @param int $_fieldSize The size of a field in pixels
	 *
	 * @return resource The rendered border grid
	 */
	public function renderTotalBorderGrid(array $_renderedBorderParts, int $_fieldSize)
	{
		// Create the background image
		$unusedColor = $this->transparentImageUtils->getUnusedColor($this->borderColors);
		$image = $this->initializeImage($_fieldSize, $unusedColor);

		// Render the border parts
		foreach ($_renderedBorderParts as $renderedBorderPart)
		{
			$startX = $renderedBorderPart->parentBorderPart()->startsAt()->x();
			$startY = $renderedBorderPart->parentBorderPart()->startsAt()->y();

			$imageStartX = $startX * $_fieldSize + $this->borderPositionsGrid->getTotalBorderWidthUntilColumn($startX) - $this->borderPositionsGrid->getMaximumBorderWidthInColumn($startX);
			$imageStartY = $startY * $_fieldSize + $this->borderPositionsGrid->getTotalBorderHeightUntilRow($startY) - $this->borderPositionsGrid->getMaximumBorderHeightInRow($startY);

			$rawRenderedBorderPart = $renderedBorderPart->rawRenderedBorderPart();
			imagecopy($image, $rawRenderedBorderPart, $imageStartX, $imageStartY, 0, 0, imagesx($rawRenderedBorderPart), imagesy($rawRenderedBorderPart));
		}

		imagecolortransparent($image, $unusedColor->getColor($image));

		return $image;
	}

	/**
	 * Initializes the background image of the border grid.
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels
	 * @param ImageColor $_backgroundColor The background color of the initial image
	 *
	 * @return resource The background image
	 */
	private function initializeImage(int $_fieldSize, ImageColor $_backgroundColor)
	{
		$highestColumnId = $this->borderPositionsGrid->getHighestColumnId();
		$highestRowId = $this->borderPositionsGrid->getHighestRowId();

		// TODO: Find way to omit board width here
		$imageWidth = $this->board->width() * $_fieldSize + $this->borderPositionsGrid->getTotalBorderWidthUntilColumn($highestColumnId);
		$imageHeight = $this->board->height() * $_fieldSize + $this->borderPositionsGrid->getTotalBorderHeightUntilRow($highestRowId);

		$image = imagecreatetruecolor($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $_backgroundColor->getColor($image));

		return $image;
	}
}
