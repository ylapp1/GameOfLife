<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border;

use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Image\Border\Shapes\ImageRectangleBorderShape;
use BoardRenderer\Image\Utils\ImageColor;
use Simulator\Board;
use Util\Geometry\Coordinate;
use Util\Geometry\Rectangle;

/**
 * The outer border of the board for images.
 */
class ImageBoardOuterBorder extends ImageBorder
{
	// Magic Methods

	/**
	 * ImageBoardOuterBorder constructor.
	 *
	 * @param Board $_board The board for which the outer border will be created
	 * @param ImageColor $_gridColor The color of the grid (and the borders)
	 */
	public function __construct(Board $_board, ImageColor $_gridColor)
	{
		$topLeftCornerCoordinate = new Coordinate(0, 0);
		$bottomRightCornerCoordinate = new Coordinate($_board->width() - 1, $_board->height() - 1);
		$rectangle = new Rectangle($topLeftCornerCoordinate, $bottomRightCornerCoordinate);

		parent::__construct(
			null,
			new ImageRectangleBorderShape(
				$this,
				$rectangle,
				new BorderPartThickness(1, 15),
				new BorderPartThickness(15, 1)
			),
			$_gridColor
		);
	}
}
