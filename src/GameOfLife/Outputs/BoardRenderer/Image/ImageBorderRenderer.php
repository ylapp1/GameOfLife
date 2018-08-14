<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Image;

use GameOfLife\Board;
use Output\BoardRenderer\Base\BaseBorderRenderer;
use Output\Helpers\ImageColor;

/**
 * Class ImageBorderRenderer
 */
class ImageBorderRenderer extends BaseBorderRenderer
{
	private $fieldSize;

	private $gridColor;

	private $baseImage;

	private $gridImage;


	public function __construct(bool $_hasBackgroundGrid = true, Board $_board, int $_fieldSize, ImageColor $_backgroundColor, ImageColor $_gridColor)
	{
		$this->fieldSize = $_fieldSize;
		$this->gridColor = $_gridColor;

		$this->baseImage = imagecreate($_board->width() * $this->fieldSize, $_board->height() * $this->fieldSize);
		imagefill($this->baseImage, 0, 0, $_backgroundColor->getColor($this->baseImage));

		$this->gridImage = $this->initializeGrid($_hasBackgroundGrid, $_board);
	}

	/**
	 * Initializes and returns the background image of each image (an empty grid).
	 *
	 * Requires the class attribute $cellSize to be set
	 *
	 * @param Bool $_hasBackgroundGrid If true, the grid will be initialized as a background grid, else it will be an empty grid
	 * @param Board $_board The board
	 *
	 * @return resource The initialized grid
	 */
	protected function initializeGrid(Bool $_hasBackgroundGrid = false, Board $_board = null)
	{

		if ($_board && $_hasBackgroundGrid)
		{
			$gridImage = $this->baseImage;

			// draw grid
			imagesetthickness($gridImage, 1);

			// Vertical lines
			for ($x = 0; $x < $_board->width() * $this->fieldSize; $x += $this->fieldSize)
			{
				imageline($gridImage, $x, 0, $x, imagesy($gridImage), $this->gridColor->getColor($gridImage));
			}

			// Horizontal lines
			for ($y = 0; $y < $_board->height() * $this->fieldSize; $y += $this->fieldSize)
			{
				imageline($gridImage, 0, $y, imagesx($gridImage), $y, $this->gridColor->getColor($gridImage));
			}

			imagesetthickness($gridImage, 1);

			return $gridImage;
		}
		else return null;
	}

	public function getRenderedBorderGrid($_border = null)
	{
		return $this->gridImage;
	}
}
