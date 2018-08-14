<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\CellImage;

use Output\Helpers\ImageColor;

/**
 * Parent class for cell images that use a transparent background.
 */
abstract class TransparentCellImage extends BaseCellImage
{
	/**
	 * Returns a color that is not used elsewhere in the image.
	 * This color can then be used for the imagecolortransparent() function.
	 */
	protected function getUnusedColor()
	{
		$unusedColorRed = 0;
		while ($unusedColorRed == $this->backgroundColor->red() || $unusedColorRed == $this->foreGroundColor->red())
		{
			$unusedColorRed++;
		}

		$transparentColor = new ImageColor($unusedColorRed, 0, 0);

		return $transparentColor;
	}
}
