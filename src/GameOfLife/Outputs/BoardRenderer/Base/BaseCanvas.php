<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

/**
 * Canvas on which borders and cells can be drawn.
 */
abstract class BaseCanvas
{
	// Attributes

	/**
	 * The border grid that was created by the border renderer
	 *
	 * @var BaseBorderGrid $borderGrid
	 */
	protected $borderGrid;


    // Class Methods

    /**
     * Resets the content of the canvas.
     */
    abstract public function reset();

    /**
     * Adds the rendered border grid to the canvas.
     *
     * @param BaseBorderGrid $_borderGrid The border grid
     */
    abstract public function addBorderGrid($_borderGrid);

    /**
     * Adds the rendered board fields to the canvas.
     *
     * @param mixed[][] $_renderedBoardFields The list of rendered board fields
     */
    abstract public function addRenderedBoardFields(array $_renderedBoardFields);

    /**
     * Returns the content of the canvas.
     *
     * @return mixed The content of the canvas
     */
    abstract public function getContent();
}
