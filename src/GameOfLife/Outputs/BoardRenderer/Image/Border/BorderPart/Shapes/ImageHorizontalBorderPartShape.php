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
		$xEnd = $this->parentBorderPart->endsAt()->x();
		$maximumAllowedXCoordinate = $this->parentBorderPart->parentBorder()->shape()->getMaximumAllowedXCoordinate($this->parentBorderPart->startsAt()->y());
		if ($xEnd > $maximumAllowedXCoordinate) $xEnd = $maximumAllowedXCoordinate;

		$borderPartWidth = $xEnd - $this->parentBorderPart->startsAt()->x() + 1;

		/** @var ImageBorder $parentBorder */
		$parentBorder = $this->parentBorderPart->parentBorder();

		$fieldSize = $parentBorder->fieldSize();
		$thickness = $this->parentBorderPart->thickness();

		$additionalPixels = 0;
		foreach ($this->parentBorderPart->getCollisionThicknesses() as $collisionThickness)
		{
			$additionalPixels += $collisionThickness->width();
		}

		$imageWidth = $borderPartWidth * $fieldSize * $thickness->width() + $additionalPixels;
		$imageHeight = $thickness->height();

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $parentBorder->color()->getColor($image));

		return $image;
	}
}
