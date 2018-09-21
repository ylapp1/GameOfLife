<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\Shapes\BaseBorderShape;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageHorizontalBorderPartShape;
use GameOfLife\Coordinate;

/**
 * Horizontal border part for images.
 */
class ImageHorizontalBorderPart extends BaseBorderPart
{
	// Magic Methods

	/**
	 * ImageHorizontalBaseBorderPart constructor.
	 *
	 * @param BaseBorderShape $_parentBorderShape The parent border shape
	 * @param Coordinate $_startsAt The start coordinate
	 * @param Coordinate $_endsAt The end coordinate
	 * @param BorderPartThickness $_thickness The thickness
	 */
	public function __construct($_parentBorderShape, Coordinate $_startsAt, Coordinate $_endsAt, BorderPartThickness $_thickness)
	{
		parent::__construct($_parentBorderShape, $_startsAt, $_endsAt, new ImageHorizontalBorderPartShape($this), $_thickness);
	}
}