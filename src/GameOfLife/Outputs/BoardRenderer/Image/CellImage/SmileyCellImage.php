<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Image\CellImage;

use Output\Helpers\ImageColor;

/**
 * Creates and returns a cell smiley image.
 */
class SmileyCellImage extends TransparentCellImage
{
	// Magic Methods

	/**
	 * BaseCellImage constructor.
	 *
	 * @param ImageColor $_backgroundColor The background color of the cell image
	 * @param ImageColor $_foreGroundColor The foreground color of the cell image
	 * @param int $_height The height of the image
	 * @param int $_width The width of the image
	 */
	public function __construct(ImageColor $_backgroundColor, ImageColor $_foreGroundColor, int $_height, int $_width)
	{
		parent::__construct($_backgroundColor, $_foreGroundColor, $_height, $_width);
	}


	// Class Methods

	/**
	 * Creates and returns a cell smiley image.
	 *
	 * @return resource The cell image
	 */
	public function getImage()
	{
		$transparentColor = $this->getUnusedColor();

		$cellImage = imagecreatetruecolor($this->width, $this->height);
		imagefill($cellImage, 0, 0, $transparentColor->getColor($cellImage));

		// Head
		imagefilledellipse($cellImage, $this->width / 2, $this->height / 2, $this->width, $this->height, $this->foreGroundColor->getColor($cellImage));
		// Eyes
		imagefilledellipse($cellImage, $this->width / 4, $this->height / 4, $this->width / 4, $this->height / 4, $this->backgroundColor->getColor($cellImage));
		imagefilledellipse($cellImage, $this->width * 3/4, $this->height / 4, $this->width / 4, $this->height / 4, $this->backgroundColor->getColor($cellImage));
		// Mouth
		imagesetthickness($cellImage, 5);
		imagearc($cellImage, $this->width / 2, $this->height / 2, $this->width * 3/4,$this->height * 3/4, 25, 155, $this->backgroundColor->getColor($cellImage));

		imagecolortransparent($cellImage, $transparentColor->getColor($cellImage));

		return $cellImage;
	}
}
