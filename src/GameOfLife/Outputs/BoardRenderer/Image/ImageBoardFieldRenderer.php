<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Image;

use GameOfLife\Coordinate;
use GameOfLife\Field;
use Output\BoardRenderer\Base\BaseBoardFieldRenderer;

/**
 * Class that renders board fields for images.
 */
class ImageBoardFieldRenderer extends BaseBoardFieldRenderer
{
	// Attributes

	/**
	 * The size of a field in pixels
	 *
	 * @var int $fieldSize
	 */
	private $fieldSize;


	// Magic Methods

	/**
	 * ImageBoardFieldRenderer constructor.
	 *
	 * @param int $_fieldSize The size of a field in pixels
	 * @param resource $_cellAliveImage The image that will be used to render living cells
	 * @param resource $_cellDeadImage The image that will be used to render dead cells
	 */
	public function __construct(int $_fieldSize, $_cellAliveImage = null, $_cellDeadImage = null)
	{
		parent::__construct($_cellAliveImage, $_cellDeadImage);
		$this->fieldSize = $_fieldSize;
	}


	// Class Methods

	/**
	 * Calculates and returns the position of the board field on the canvas.
	 *
	 * @param Field $_field The field
	 *
	 * @return Coordinate The position of the board field on the canvas
	 */
	public function getBoardFieldCanvasPosition(Field $_field): Coordinate
	{
		// TODO: Cannot know if the position must be shifted because of borders
		return new Coordinate(
			$_field->coordinate()->x(),
			$_field->coordinate()->y()
		);
	}
}
