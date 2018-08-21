<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBoardFieldRenderer;

/**
 * Renders the board fields for images.
 */
class ImageBoardFieldRenderer extends BaseBoardFieldRenderer
{
	// Magic Methods

	/**
	 * BaseBoardFieldRenderer constructor.
	 *
	 * @param resource $_renderedCellAlive The rendered cell for alive cells
	 * @param resource $_renderedCellDead The rendered cell for dead cells
	 */
	public function __construct($_renderedCellAlive = null, $_renderedCellDead = null)
	{
		parent::__construct($_renderedCellAlive, $_renderedCellDead);
	}
}
