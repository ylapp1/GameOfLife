<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

/**
 * Canvas on which borders and cells can be drawn.
 */
abstract class BaseCanvas
{
    // Class Methods

    /**
     * Resets the content of the canvas.
     */
    abstract public function reset();

    /**
     * Adds the rendered border grid to the canvas.
     *
     * @param mixed[][] $_renderedBorderGrid The rendered border grid
     */
    abstract public function addRenderedBorderGrid($_renderedBorderGrid);

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
