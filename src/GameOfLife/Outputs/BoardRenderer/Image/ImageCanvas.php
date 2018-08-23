<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseCanvas;
use BoardRenderer\Image\Utils\ImageColor;

/**
 * Combines resources to an total image.
 */
class ImageCanvas extends BaseCanvas
{
	// Attributes

	/**
	 * The background color of the image
	 *
	 * @var ImageColor $backgroundColor
	 */
	private $backgroundColor;


	// Magic Methods

	/**
	 * ImageBorderGrid constructor.
	 *
	 * @param ImageColor $_backgroundColor The background color of the border grid
	 * @param Bool $_cachesBorderGrid The board for which the border grid is created
	 */
	public function __construct(ImageColor $_backgroundColor, Bool $_cachesBorderGrid = true)
	{
		parent::__construct($_cachesBorderGrid);
		$this->backgroundColor = $_backgroundColor;
	}


	// Class Methods

	/**
	 * Renders the total board (combines board fields and border grid).
	 * This method must be called after setBorderGrid() and setRenderedBoardFields() were called
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels
	 *
	 * @return mixed The total rendered board
	 */
	public function render(int $_fieldSize)
	{
		$renderedBorderGrid = $this->getRenderedBorderGrid($_fieldSize);

		$renderedBorderGridWidth = imagesx($renderedBorderGrid);
		$renderedBorderGridHeight = imagesy($renderedBorderGrid);

		$totalBoardImage = imagecreate($renderedBorderGridWidth, $renderedBorderGridHeight);
		imagefill($totalBoardImage, 0, 0, $this->backgroundColor->getColor($totalBoardImage));
		imagecopy($totalBoardImage, $renderedBorderGrid, 0, 0, 0, 0, $renderedBorderGridWidth, $renderedBorderGridHeight);

		foreach ($this->renderedBoardFields as $y => $renderedBoardFieldRow)
		{
			foreach ($renderedBoardFieldRow as $x => $renderedBoardField)
			{
				$imageWidth = imagesx($renderedBoardField);
				$imageHeight = imagesy($renderedBoardField);

				$fieldStartX = $x * $_fieldSize + $this->borderGrid->getTotalBorderWidthUntilColumn($x);
				$fieldStartY = $y * $_fieldSize + $this->borderGrid->getTotalBorderHeightUntilRow($y);

				// Center the cell image
				$cellStartX = $fieldStartX + ($_fieldSize - $imageWidth) / 2;
				$cellStartY = $fieldStartY + ($_fieldSize - $imageHeight) / 2;

				imagecopy($totalBoardImage, $renderedBoardField, $cellStartX, $cellStartY, 0, 0, $imageWidth, $imageHeight);
			}
		}

		return $totalBoardImage;
	}
}
