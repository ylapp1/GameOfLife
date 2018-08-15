<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseVerticalBorderPartShape;
use BoardRenderer\Image\Border\BorderPart\ImageBorderPart;

/**
 * Creates and returns the image for a vertical border part.
 */
class ImageVerticalBorderPartShape extends BaseVerticalBorderPartShape
{
	// Attributes

	/**
	 * The parent border part
	 *
	 * @var ImageBorderPart $parentBorderPart
	 */
	protected $parentBorderPart;


	// Class Methods

	protected function getRawRenderedBorderPart()
	{
		$fieldSize = $this->parentBorderPart->parentBorder()->fieldSize();
		$borderPartHeight = $this->parentBorderPart->endsAt()->y() - $this->parentBorderPart->startsAt()->y() + 1;
		$additionalPixels = count($this->parentBorderPart->getCollisionPositions());

		// TODO: Width = thickness
		$image = imagecreate(1, $borderPartHeight * $fieldSize + $additionalPixels);
		imagefill($image, 0, 0, $this->parentBorderPart->parentBorder()->gridColor()->getColor($image));

		// You could also use imagesetthickness(1) and then imageline() to draw the image on an already existing image

		return $image;
	}
}
