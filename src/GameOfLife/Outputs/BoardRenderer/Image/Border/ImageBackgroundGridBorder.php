<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border;

use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Image\Border\Shapes\ImageGridBorderShape;
use Output\Helpers\ImageColor;

/**
 * The background grid border for images.
 */
class ImageBackgroundGridBorder extends ImageBorder
{
	// Magic Methods

	/**
	 * ImageBackgroundGridBorder constructor.
	 *
	 * @param ImageBorder $_parentBorder The parent border in which the background grid will be created
	 * @param ImageColor $_gridColor The color of the grid (and the borders)
	 */
	public function __construct(ImageBorder $_parentBorder, ImageColor $_gridColor)
	{
		parent::__construct(
			$_parentBorder,
			new ImageGridBorderShape(
				$this,
				new BorderPartThickness(1, 1),
				new BorderPartThickness(1, 1)
			),
			$_gridColor
		);
	}
}
