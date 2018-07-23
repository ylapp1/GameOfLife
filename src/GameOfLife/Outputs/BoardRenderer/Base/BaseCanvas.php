<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use GameOfLife\Coordinate;

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
     * Adds a rendered border at a specific position to the canvas.
     *
     * @param mixed $_renderedBorder The rendered border
     * @param Coordinate $_at The position at which the rendered border will be added to the canvas
     */
    abstract public function addRenderedBorderAt($_renderedBorder, Coordinate $_at);

    /**
     * Adds a rendered board field at a specific position to the canvas.
     *
     * @param mixed $_renderedBoardField The rendered board field
     * @param Coordinate $_at The position at which the rendered board field will be added to the canvas
     */
    abstract public function addRenderedBoardFieldAt($_renderedBoardField, Coordinate $_at);

    /**
     * Returns the content of the canvas.
     *
     * @return mixed The content of the canvas
     */
    abstract public function getContent();
}
