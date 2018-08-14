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
 * The smiley image looks like a regular smiling smiley (like this: ☻)
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
		imagefilledellipse($cellImage, $this->width * 0.5, $this->height * 0.5, $this->width - 1, $this->height - 1, $this->foreGroundColor->getColor($cellImage));

		// Eyes
		imagefilledellipse($cellImage, $this->width * 0.25, $this->height * 3/8, $this->width * 0.2, $this->height * 0.2, $this->backgroundColor->getColor($cellImage));
		imagefilledellipse($cellImage, $this->width * 0.75, $this->height * 3/8, $this->width * 0.2, $this->height * 0.2, $this->backgroundColor->getColor($cellImage));

		// Mouth
		imagesetthickness($cellImage, 5);
		imagearc($cellImage, $this->width * 0.5, $this->height * 5/8, $this->width * 0.6,$this->height * 0.5, 10, 170, $this->backgroundColor->getColor($cellImage));

		imagecolortransparent($cellImage, $transparentColor->getColor($cellImage));

		return $cellImage;
	}
}
