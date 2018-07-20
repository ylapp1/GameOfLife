<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\BorderPartShapes;

use Output\BoardRenderer\Base\BaseBorderRenderer;

/**
 * Stores information about a specific border part shape.
 */
abstract class BaseBorderPartShape
{
	// Class Methods

	/**
	 * Adds all borders of this border shape to a border renderer.
	 *
	 * @param BaseBorderRenderer $_borderRenderer The border renderer
	 */
	abstract public function addBorderPartsToBorderRenderer(BaseBorderRenderer $_borderRenderer);
}
