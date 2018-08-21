<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBorderGrid;
use GameOfLife\Board;
use Output\Helpers\ImageColor;

/**
 * Rendered border grid for the image board renderer.
 */
class ImageBorderGrid extends BaseBorderGrid
{
	// Attributes

	/**
	 * The background color of the border grid
	 *
	 * @var ImageColor $backgroundColor
	 */
	private $backgroundColor;


	// Magic Methods

	/**
	 * ImageBorderGrid constructor.
	 *
	 * @param Board $_board The board for which the border grid is created
	 * @param ImageColor $_backgroundColor The background color of the border grid
	 */
	public function __construct(Board $_board, ImageColor $_backgroundColor)
	{
		parent::__construct($_board);
		$this->backgroundColor = $_backgroundColor;
	}


	// Class Methods

	/**
	 * Creates and returns the rendered border grid.
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels
	 *
	 * @return resource The rendered border grid
	 */
	public function renderBorderGrid(int $_fieldSize)
	{
		$this->renderBorderParts($_fieldSize);

		// Create the background image
		$image = $this->initializeImage($_fieldSize);

		// Render the border parts
		foreach ($this->renderedBorderParts as $renderedBorderPart)
		{
			$startX = $renderedBorderPart->parentBorderPart()->startsAt()->x();
			$startY = $renderedBorderPart->parentBorderPart()->startsAt()->y();

			$imageStartX = $startX * $_fieldSize + $this->getTotalBorderWidthUntilColumn($startX) - $this->getMaximumBorderWidthInColumn($startX);
			$imageStartY = $startY * $_fieldSize + $this->getTotalBorderHeightUntilRow($startY) - $this->getMaximumBorderHeightInRow($startY);

			$rawRenderedBorderPart = $renderedBorderPart->rawRenderedBorderPart();
			imagecopy($image, $rawRenderedBorderPart, $imageStartX, $imageStartY, 0, 0, imagesx($rawRenderedBorderPart), imagesy($rawRenderedBorderPart));
		}

		return $image;
	}

	/**
	 * Initializes the background image of the border grid.
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels
	 *
	 * @return resource The background image
	 */
	private function initializeImage(int $_fieldSize)
	{
		$imageWidth = $this->board->width() * $_fieldSize + $this->getTotalBorderWidthUntilColumn($this->getHighestColumnId());
		$imageHeight = $this->board->height() * $_fieldSize + $this->getTotalBorderHeightUntilRow($this->getHighestRowId());

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $this->backgroundColor->getColor($image));

		return $image;
	}
}
