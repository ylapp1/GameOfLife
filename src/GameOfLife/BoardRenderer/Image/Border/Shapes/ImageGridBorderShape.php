<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\Shapes;

use BoardRenderer\Base\Border\Shapes\BaseGridBorderShape;
use BoardRenderer\Image\Border\BorderPart\ImageHorizontalBorderPart;
use BoardRenderer\Image\Border\BorderPart\ImageVerticalBorderPart;
use GameOfLife\Coordinate;

/**
 * Border shape for image background grids.
 */
class ImageGridBorderShape extends BaseGridBorderShape
{
	// Class Methods

	/**
	 * Returns a horizontal border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 *
	 * @return ImageHorizontalBorderPart The horizontal border part
	 */
	protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt): ImageHorizontalBorderPart
	{
		return new ImageHorizontalBorderPart(
			$this,
			$_startsAt,
			$_endsAt,
			$this->horizontalBorderPartsThickness
		);
	}

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 *
	 * @return ImageVerticalBorderPart The vertical border part
	 */
	protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt): ImageVerticalBorderPart
	{
		return new ImageVerticalBorderPart(
			$this,
			$_startsAt,
			$_endsAt,
			$this->verticalBorderPartsThickness
		);
	}
}
