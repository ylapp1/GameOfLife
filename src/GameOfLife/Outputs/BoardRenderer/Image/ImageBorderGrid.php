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
	 * @param int $_fieldSize The height/width of a single field
	 * @param ImageColor $_backgroundColor The background color of the border grid
	 */
	public function __construct(Board $_board, int $_fieldSize, ImageColor $_backgroundColor)
	{
		parent::__construct($_board, $_fieldSize);
		$this->backgroundColor = $_backgroundColor;
	}


	// Class Methods

	/**
	 * Creates and returns the rendered border grid.
	 *
	 * @return resource The rendered border grid
	 */
	public function renderBorderGrid()
	{
		$this->renderBorderParts();

		// Create the background image
		$image = $this->initializeImage();

		// Render the border parts
		foreach ($this->renderedBorderParts as $renderedBorderPart)
		{
			$startX = $renderedBorderPart->parentBorderPart()->startsAt()->x();
			$startY = $renderedBorderPart->parentBorderPart()->startsAt()->y();

			$parentBorderShape = $renderedBorderPart->parentBorderPart()->parentBorder()->shape();

			$imageStartX = $startX * $this->fieldSize + $this->getTotalBorderWidthUntilColumn($startX) - $parentBorderShape->getBorderWidthInColumn($startX);
			$imageStartY = $startY * $this->fieldSize + $this->getTotalBorderHeightUntilRow($startY) - $parentBorderShape->getBorderHeightInRow($startY);

			$rawRenderedBorderPart = $renderedBorderPart->rawRenderedBorderPart();
			imagecopy($image, $rawRenderedBorderPart, $imageStartX, $imageStartY, 0, 0, imagesx($rawRenderedBorderPart), imagesy($rawRenderedBorderPart));
		}

		return $image;
	}

	/**
	 * Initializes the background image of the border grid.
	 *
	 * @return resource The background image
	 */
	private function initializeImage()
	{
		$imageWidth = $this->board->width() * $this->fieldSize + $this->getTotalBorderWidthUntilColumn($this->getHighestColumnId());
		$imageHeight = $this->board->height() * $this->fieldSize + $this->getTotalBorderHeightUntilRow($this->getHighestRowId());

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $this->backgroundColor->getColor($image));

		return $image;
	}
}
