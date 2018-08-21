<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Base\Border\Shapes\BaseGridBorderShape;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageHorizontalBorderPartShape;
use BoardRenderer\Image\Border\BorderPart\Shapes\ImageVerticalBorderPartShape;
use BoardRenderer\Image\Border\ImageBorder;
use GameOfLife\Coordinate;

/**
 * Border shape for image background grids.
 */
class ImageGridBorderShape extends BaseGridBorderShape
{
	/**
	 * Returns a horizontal border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param ImageBorder $_parentBorder The outer border
	 *
	 * @return BorderPart The horizontal border part
	 */
	protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder): BorderPart
	{
		return new BorderPart(
			$_parentBorder,
			$_startsAt,
			$_endsAt,
			new ImageHorizontalBorderPartShape(),
			$this->horizontalThickness
		);
	}

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param ImageBorder $_parentBorder The outer border
	 *
	 * @return BorderPart The vertical border part
	 */
	protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder): BorderPart
	{
		return new BorderPart(
			$_parentBorder,
			$_startsAt,
			$_endsAt,
			new ImageVerticalBorderPartShape(),
			$this->verticalThickness
		);
	}
}
