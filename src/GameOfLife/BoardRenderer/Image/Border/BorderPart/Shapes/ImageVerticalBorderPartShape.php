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
	 * @param int $_fieldSize The height/width of a single field in pixels
	 *
	 * @return resource The rendered parent border part image
	 */
	protected function getRawRenderedBorderPart(int $_fieldSize)
	{
		$endY = $this->parentBorderPart->endsAt()->y();
		$parentBorderShapeEndY = $this->parentBorderPart->parentBorderShape()->getEndY($this->parentBorderPart->startsAt()->x());
		if ($endY > $parentBorderShapeEndY) $endY = $parentBorderShapeEndY;

		$borderPartHeight = $endY - $this->parentBorderPart->startsAt()->y() + 1;

		/** @var ImageBorder $parentBorder */
		$parentBorder = $this->parentBorderPart->parentBorderShape()->parentBorder();
		$thickness = $this->parentBorderPart->thickness();

		$numberOfAdditionalPixels = 0;
		foreach ($this->parentBorderPart->getCollisionThicknesses() as $collisionThickness)
		{
			$numberOfAdditionalPixels += $collisionThickness->height();
		}

		$imageWidth = $thickness->width();
		$imageHeight = $borderPartHeight * $_fieldSize * $thickness->height() + $numberOfAdditionalPixels;

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $parentBorder->color()->getColor($image));

		return $image;
	}
}