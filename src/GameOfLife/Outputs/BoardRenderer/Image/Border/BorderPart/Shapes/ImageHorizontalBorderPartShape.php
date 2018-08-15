<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseHorizontalBorderPartShape;
use BoardRenderer\Image\Border\BorderPart\ImageBorderPart;

/**
 * Creates and returns the image for a horizontal border part.
 */
class ImageHorizontalBorderPartShape extends BaseHorizontalBorderPartShape
{
	// Attributes

	/**
	 * The parent border part
	 *
	 * @var ImageBorderPart $parentBorderPart
	 */
	protected $parentBorderPart;


	// Class Methods

	/**
	 * Creates and returns the rendered parent border part image.
	 *
	 * @return resource The rendered parent border part image
	 */
	protected function getRawRenderedBorderPart()
	{
		$fieldSize = $this->parentBorderPart->parentBorder()->fieldSize();
		$borderPartWidth = $this->parentBorderPart->endsAt()->x() - $this->parentBorderPart->startsAt()->x() + 1;
		$additionalPixels = count($this->parentBorderPart->getCollisionPositions());

		$imageWidth = ($borderPartWidth * $fieldSize) * $this->parentBorderPart->thickness()->width() + $additionalPixels;
		$imageHeight = $this->parentBorderPart->thickness()->height();

		$image = imagecreate($imageWidth, $imageHeight);
		imagefill($image, 0, 0, $this->parentBorderPart->parentBorder()->gridColor()->getColor($image));

		return $image;
	}
}
