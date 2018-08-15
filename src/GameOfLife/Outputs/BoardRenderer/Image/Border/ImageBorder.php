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
 * Renders borders as resources.
 */
class ImageBorder extends BaseBorder
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
	 * @param BaseBorder|null $_parentBorder
	 * @param BaseBorderShape $_shape
	 * @param ImageColor $_gridColor
	 * @param int $_fieldSize
	 */
	public function __construct(BaseBorder $_parentBorder = null, BaseBorderShape $_shape, ImageColor $_gridColor, int $_fieldSize)
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

	public function fieldSize(): int
	{
		return $this->fieldSize;
	}
}
