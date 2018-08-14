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
 * Canvas that draws images onto a resource.
 */
class ImageCanvas extends BaseCanvas
{
	// Constructor

	/**
	 * The background image of the canvas.
	 *
	 * @var resource $backgroundImage
	 */
	private $backgroundImage;

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
	 * @param int $_fieldSize The field size in pixels
	 */
	public function __construct(int $_fieldSize)
	{
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
	 * Adds the rendered border grid to the canvas.
	 *
	 * @param resource $_renderedBorderGrid The rendered border grid
	 */
	public function addRenderedBorderGrid($_renderedBorderGrid)
	{
		$this->backgroundImage = $_renderedBorderGrid;
	}

	/**
	 * Adds the rendered board fields to the total image.
	 *
	 * @param resource[][] $_renderedBoardFields The list of rendered board fields
	 */
	public function addRenderedBoardFields(array $_renderedBoardFields)
	{
		$backgroundImageWidth = imagesx($this->backgroundImage);
		$backgroundImageHeight = imagesy($this->backgroundImage);

		$this->image = imagecreate($backgroundImageWidth, $backgroundImageHeight);
		imagecopy($this->image, $this->backgroundImage, 0,0,0,0, $backgroundImageWidth, $backgroundImageHeight);

		foreach ($_renderedBoardFields as $y => $renderedBoardFieldRow)
		{
			foreach ($renderedBoardFieldRow as $x => $renderedBoardField)
			{
				if ($renderedBoardField)
				{
					$imageWidth = imagesx($renderedBoardField);
					$imageHeight = imagesy($renderedBoardField);

					// Center the images
					$startX = $x * $this->fieldSize + ($this->fieldSize - $imageWidth) / 2;
					$startY = $y * $this->fieldSize + ($this->fieldSize - $imageHeight) / 2;

					imagecopymerge($this->image, $renderedBoardField, $startX, $startY, 0, 0, $imageWidth, $imageHeight, 100);
				}
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
