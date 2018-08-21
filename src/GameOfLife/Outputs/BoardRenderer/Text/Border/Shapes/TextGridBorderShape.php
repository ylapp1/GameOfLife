<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\Shapes;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\Shapes\BaseGridBorderShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextVerticalCollisionBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use GameOfLife\Coordinate;

/**
 * The text border shape for background grids.
 */
class TextGridBorderShape extends BaseGridBorderShape
{
	/**
	 * Returns a horizontal border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param BaseBorder $_parentBorder The main border
	 *
	 * @return TextBorderPart The horizontal border part
	 */
	protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder)
	{
		// TODO: Add border symbol definitions

		return new TextBorderPart(
			$_parentBorder,
			$_startsAt,
			$_endsAt,
			new TextHorizontalBorderPartShape(),
			new BorderPartThickness(1, 1),
			""
		);
	}

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param BaseBorder $_parentBorder The main border
	 *
	 * @return TextBorderPart The vertical border part
	 */
	protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder)
	{
		// TODO: Add border symbol definitions

		return new TextBorderPart(
			$_parentBorder,
			$_startsAt,
			$_endsAt,
			new TextVerticalCollisionBorderPartShape(),
			new BorderPartThickness(1, 1),
			""
		);
	}
}
