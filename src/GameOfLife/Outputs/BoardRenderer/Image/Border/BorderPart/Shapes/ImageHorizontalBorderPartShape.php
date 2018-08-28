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
	 * @param int $_fieldSize The height/width of a single field in pixels
	 *
	 * @return resource The rendered parent border part image
	 */
	protected function getRawRenderedBorderPart(int $_fieldSize)
	{
		$endX = $this->parentBorderPart->endsAt()->x();
		$parentBorderShapeEndX = $this->parentBorderPart->parentBorderShape()->getEndX($this->parentBorderPart->startsAt()->y());
		if ($endX > $parentBorderShapeEndX) $endX = $parentBorderShapeEndX;

		$borderPartWidth = $endX - $this->parentBorderPart->startsAt()->x() + 1;

		/** @var ImageBorder $parentBorder */
		$parentBorder = $this->parentBorderPart->parentBorderShape()->parentBorder();
		$thickness = $this->parentBorderPart->thickness();

		$numberOfAdditionalPixels = 0;
		foreach ($this->parentBorderPart->getCollisionThicknesses() as $collisionThickness)
		{
			$numberOfAdditionalPixels += $collisionThickness->width();
		}

		$imageWidth = $borderPartWidth * $_fieldSize * $thickness->width() + $numberOfAdditionalPixels;
		$imageHeight = $thickness->height();

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $parentBorder->color()->getColor($image));

		return $image;
	}
}
