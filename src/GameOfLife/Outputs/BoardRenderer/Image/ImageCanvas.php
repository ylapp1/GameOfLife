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
	private $backgroundImage;
	private $image;

	private $fieldSize;


	public function __construct(int $_fieldSize)
	{
		$this->fieldSize = $_fieldSize;
	}

	public function addRenderedBorderGrid($_renderedBorderGrid)
	{
		$this->backgroundImage = $_renderedBorderGrid;
	}

	public function addRenderedBoardFields(array $_renderedBoardFields)
	{
		$this->image = imagecreate(imagesx($this->backgroundImage), imagesy($this->backgroundImage));
		imagecopy($this->image, $this->backgroundImage, 0,0,0,0,imagesx($this->backgroundImage), imagesy($this->backgroundImage));

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

	public function getContent()
	{
		return $this->image;
	}

	public function reset()
	{
		$this->image = null;
	}
}
