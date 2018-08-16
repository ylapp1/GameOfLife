<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseHorizontalBorderPartShape;
use BoardRenderer\Image\Border\ImageBorder;

/**
 * Creates and returns the image for a horizontal border part.
 */
class ImageHorizontalBorderPartShape extends BaseHorizontalBorderPartShape
{
	// Class Methods

	/**
	 * Creates and returns the rendered parent border part image.
	 *
	 * @return resource The rendered parent border part image
	 */
	protected function getRawRenderedBorderPart()
	{
		$borderPartWidth = $this->parentBorderPart->endsAt()->x() - $this->parentBorderPart->startsAt()->x() + 1;

		/** @var ImageBorder $parentBorder */
		$parentBorder = $this->parentBorderPart->parentBorder();

		// TODO: Find better place to store field size!
		// TODO: Field size should be in base classes
		$fieldSize = $parentBorder->fieldSize();
		$thickness = $this->parentBorderPart->thickness();

		// TODO: Calculate total collision position width
		$additionalPixels = count($this->parentBorderPart->getCollisionPositions());

		$imageWidth = $borderPartWidth * $fieldSize * $thickness->width() + $additionalPixels;
		$imageHeight = $thickness->height();

		$image = imagecreate($imageWidth, $imageHeight);

		// TODO: Rename gridColor attribute to color
		// TODO: Grid color should be in base classes too (maybe)
		imagefill($image, 0, 0, $parentBorder->gridColor()->getColor($image));

		return $image;
	}
}
