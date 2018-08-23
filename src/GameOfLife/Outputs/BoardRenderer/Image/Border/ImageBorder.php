<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Border;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\Shapes\BaseBorderShape;
use BoardRenderer\Image\Utils\ImageColor;

/**
 * Renders borders as images.
 */
abstract class ImageBorder extends BaseBorder
{
	// Attributes

	/**
	 * The border color
	 *
	 * @var ImageColor $color
	 */
	private $color;


	// Magic Methods

	/**
	 * ImageBorder constructor.
	 *
	 * @param ImageBorder|null $_parentBorder The parent border
	 * @param BaseBorderShape $_shape The border shape
	 * @param ImageColor $_gridColor The grid color (and border color)
	 */
	public function __construct(ImageBorder $_parentBorder = null, BaseBorderShape $_shape, ImageColor $_gridColor)
	{
		parent::__construct($_parentBorder, $_shape);
		$this->color = $_gridColor;
	}


	// Getters and Setters

	/**
	 * Returns the border color.
	 *
	 * @return ImageColor The border color
	 */
	public function color(): ImageColor
	{
		return $this->color;
	}
}
