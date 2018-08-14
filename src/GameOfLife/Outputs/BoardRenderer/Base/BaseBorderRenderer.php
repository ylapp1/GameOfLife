<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use BoardRenderer\Base\Border\BaseBorder;

/**
 * Renders a border and its inner borders and adds them to a canvas.
 */
abstract class BaseBorderRenderer
{
	// Class Methods

	/**
	 * Initializes the grid.
	 *
	 * @param Bool $_hasBackgroundGrid If set to true there will be a background grid that can be overwritten by borders
	 */
	abstract protected function initializeGrid(Bool $_hasBackgroundGrid = true);

	/**
	 * Renders and returns the border grid.
	 *
	 * @param BaseBorder $_border The main border
	 *
	 * @return mixed[][] The rendered border grid
	 */
	abstract public function getRenderedBorderGrid($_border = null);
}
