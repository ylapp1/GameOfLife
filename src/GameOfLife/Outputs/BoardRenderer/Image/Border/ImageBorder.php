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
use Output\Helpers\ImageColor;

/**
 * Renders borders as images.
 * // TODO: Move stuff from this to base class (text can be colored too and have different sizes...)
 */
abstract class ImageBorder extends BaseBorder
{
	// Attributes

	/**
	 * The grid color
	 *
	 * @var ImageColor $gridColor
	 */
	private $gridColor;

	/**
	 * The height and width of a single field
	 *
	 * @var int $fieldSize
	 */
	private $fieldSize;


	// Magic Methods

	/**
	 * ImageBorder constructor.
	 *
	 * @param ImageBorder|null $_parentBorder The parent border
	 * @param BaseBorderShape $_shape The border shape
	 * @param ImageColor $_gridColor The grid color (and border color)
	 * @param int $_fieldSize The height and width of each field
	 */
	public function __construct(ImageBorder $_parentBorder = null, BaseBorderShape $_shape, ImageColor $_gridColor, int $_fieldSize)
	{
		parent::__construct($_parentBorder, $_shape);
		$this->gridColor = $_gridColor;
		$this->fieldSize = $_fieldSize;
	}


	// Getters and Setters

	/**
	 * Returns the grid color.
	 *
	 * @return ImageColor The grid color
	 */
	public function gridColor(): ImageColor
	{
		return $this->gridColor;
	}

	/**
	 * Returns the height and width of each field.
	 *
	 * @return int The height and width of each field
	 */
	public function fieldSize(): int
	{
		return $this->fieldSize;
	}
}
