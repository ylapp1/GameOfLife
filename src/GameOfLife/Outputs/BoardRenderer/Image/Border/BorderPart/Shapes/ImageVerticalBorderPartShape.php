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

		$fieldSize = $parentBorder->fieldSize();
		$thickness = $this->parentBorderPart->thickness();

		// TODO: Calculate total collision position height
		$additionalPixels = count($this->parentBorderPart->getCollisionPositions());

		$imageWidth = $thickness->width();
		$imageHeight = $borderPartHeight * $fieldSize * $thickness->height() + $additionalPixels;

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $parentBorder->color()->getColor($image));

		return $image;
	}
}
