<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseVerticalBorderPartShape;
use BoardRenderer\Image\Border\ImageBorder;

/**
 * Creates and returns the image for a vertical border part.
 */
class ImageVerticalBorderPartShape extends BaseVerticalBorderPartShape
{
	// Class Methods

	/**
	 * Creates and returns the rendered parent border part image.
	 *
	 * @return resource The rendered parent border part image
	 */
	protected function getRawRenderedBorderPart()
	{
		$borderPartHeight = $this->parentBorderPart->endsAt()->y() - $this->parentBorderPart->startsAt()->y() + 1;

		/** @var ImageBorder $parentBorder */
		$parentBorder = $this->parentBorderPart->parentBorder();

		// TODO: Find better place to store field size!
		// TODO: Field size should be in base class
		$fieldSize = $parentBorder->fieldSize();
		$thickness = $this->parentBorderPart->thickness();

		// TODO: Calculate total collision position height
		$additionalPixels = count($this->parentBorderPart->getCollisionPositions());

		$imageWidth = $thickness->width();
		$imageHeight = $borderPartHeight * $fieldSize * $thickness->height() + $additionalPixels;

		$image = imagecreate($imageWidth, $imageHeight);

		// TODO: Rename gridColor attribute to color
		// TODO: Grid color should be in base class too (maybe)
		imagefill($image, 0, 0, $parentBorder->gridColor()->getColor($image));

		return $image;
	}
}
