<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use GameOfLife\Board;
use Output\BoardRenderer\Base\Border\BaseBorder;

/**
 * Renders a board.
 *
 * Call renderBoard() to render a board
 * Call getContent() to get the rendered board
 */
abstract class BaseBoardRenderer
{
    // Attributes

    /**
     * The main border and its inner borders
     *
     * @var BaseBorder $border
     */
    protected $border;

    /**
     * The border renderer
     *
     * @var BaseBorderRenderer $borderRenderer
     */
    protected $borderRenderer;

    /**
     * The board field renderer
     *
     * @var BaseBoardFieldRenderer $boardFieldRenderer
     */
    protected $boardFieldRenderer;

    /**
     * The canvas on which the borders and board fields will be rendered
     *
     * @var BaseCanvas $canvas
     */
    protected $canvas;


    // Class Methods

    /**
     * Renders a board to the canvas of this board renderer.
     *
     * @param Board $_board The board
     */
    public function renderBoard(Board $_board)
    {
        $this->canvas->reset();

        // Render the borders
        $this->borderRenderer->renderBorder($this->border, $this->canvas);

        // Render the board fields
        $this->boardFieldRenderer->renderBoardFields($_board->fields(), $this->canvas);
    }

    /**
     * Returns the rendered board content.
     *
     * @return mixed The rendered board content
     */
    public function getContent()
    {
        return $this->canvas->getContent();
    }
}
