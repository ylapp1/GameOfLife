<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBoardFieldRenderer;
use GameOfLife\Coordinate;
use GameOfLife\Field;

/**
 * Renders the board fields for images.
 */
class ImageBoardFieldRenderer extends BaseBoardFieldRenderer
{
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
		return new Coordinate(
			$_field->coordinate()->x(),
			$_field->coordinate()->y()
		);
	}
}
