<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseCanvas;

/**
 * Draws images onto a resource.
 */
class ImageCanvas extends BaseCanvas
{
	// Attributes

	/**
	 * The total image (border and cell images combined)
	 *
	 * @var resource $image
	 */
	private $image;

	/**
	 * The field size in pixels
	 *
	 * @var int $fieldSize
	 */
	private $fieldSize;


	// Magic Methods

	/**
	 * ImageCanvas constructor.
	 *
	 * @param Bool $_cachesBorderGrid Indicates whether this canvas caches the rendered border grid
	 * @param int $_fieldSize The field size in pixels
	 */
	public function __construct(Bool $_cachesBorderGrid = false, int $_fieldSize)
	{
		parent::__construct($_cachesBorderGrid);
		$this->fieldSize = $_fieldSize;
	}


	// Class Methods

	/**
	 * Resets the total image.
	 */
	public function reset()
	{
		$this->image = null;
	}

	/**
	 * Adds the rendered board fields to the total image.
	 *
	 * @param resource[][] $_renderedBoardFields The list of rendered board fields
	 */
	public function addRenderedBoardFields(array $_renderedBoardFields)
	{
		$renderedBorderGridWidth = imagesx($this->cachedRenderedBorderGrid);
		$renderedBorderGridHeight = imagesy($this->cachedRenderedBorderGrid);

		$this->image = imagecreate($renderedBorderGridWidth, $renderedBorderGridHeight);
		imagecopy($this->image, $this->cachedRenderedBorderGrid, 0, 0, 0, 0, $renderedBorderGridWidth, $renderedBorderGridHeight);

		foreach ($_renderedBoardFields as $y => $renderedBoardFieldRow)
		{
			foreach ($renderedBoardFieldRow as $x => $renderedBoardField)
			{
				$imageWidth = imagesx($renderedBoardField);
				$imageHeight = imagesy($renderedBoardField);

				$fieldStartX = $x * $this->fieldSize + $this->borderGrid->getTotalBorderWidthUntilColumn($x);
				$fieldStartY = $y * $this->fieldSize + $this->borderGrid->getTotalBorderHeightUntilRow($y);

				// Center the cell image
				$cellStartX = $fieldStartX + ($this->fieldSize - $imageWidth) / 2;
				$cellStartY = $fieldStartY + ($this->fieldSize - $imageHeight) / 2;

				imagecopymerge($this->image, $renderedBoardField, $cellStartX, $cellStartY, 0, 0, $imageWidth, $imageHeight, 100);
			}
		}
	}

	/**
	 * Returns the content of the canvas.
	 *
	 * @return resource The content of the canvas
	 */
	public function getContent()
	{
		return $this->image;
	}
}
