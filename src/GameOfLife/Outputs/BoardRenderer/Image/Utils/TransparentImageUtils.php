<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Utils;

/**
 * Provides util methods to create transparent images.
 */
class TransparentImageUtils
{
	// Class Methods

	/**
	 * Returns a color that is not used elsewhere in the image.
	 * This color can then be used for the imagecolortransparent() function.
	 *
	 * @param ImageColor[] $_usedColors The colors that will be used in the image
	 *
	 * @return ImageColor The unique color that is not used elsewhere in the cell image
	 */
	public function getUnusedColor(array $_usedColors): ImageColor
	{
		$unusedColor = new ImageColor(0, 0, 0);

		do
		{
			$colorIsInUse = false;
			foreach ($_usedColors as $usedColor)
			{
				if ($usedColor->equals($unusedColor))
				{
					$colorIsInUse = true;
					break;
				}
			}

			if ($colorIsInUse) $unusedColor->increase();
		}
		while ($colorIsInUse);

		return $unusedColor;
	}
}
