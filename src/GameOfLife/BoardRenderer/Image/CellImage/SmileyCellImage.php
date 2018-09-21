<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\CellImage;

/**
 * Creates and returns a cell smiley image.
 * The smiley image looks like a regular smiling smiley (like this: ☻)
 */
class SmileyCellImage extends TransparentCellImage
{
	// Class Methods

	/**
	 * Creates and returns a smiley image.
	 *
	 * @return resource The smiley image
	 */
	public function getImage()
	{
		$transparentColor = $this->transparentImageUtils->getUnusedColor(array($this->color));

		$cellImage = imagecreatetruecolor($this->width, $this->height);
		imagefill($cellImage, 0, 0, $transparentColor->getColor($cellImage));

		// Head
		imagefilledellipse($cellImage, $this->width * 0.5, $this->height * 0.5, $this->width - 1, $this->height - 1, $this->color->getColor($cellImage));

		// Eyes
		imagefilledellipse($cellImage, $this->width * 0.25, $this->height * 3/8, $this->width * 0.2, $this->height * 0.2, $transparentColor->getColor($cellImage));
		imagefilledellipse($cellImage, $this->width * 0.75, $this->height * 3/8, $this->width * 0.2, $this->height * 0.2, $transparentColor->getColor($cellImage));

		// Mouth
		imagesetthickness($cellImage, 5);
		imagearc($cellImage, $this->width * 0.5, $this->height * 5/8, $this->width * 0.6,$this->height * 0.5, 10, 170, $transparentColor->getColor($cellImage));

		imagecolortransparent($cellImage, $transparentColor->getColor($cellImage));

		return $cellImage;
	}
}
