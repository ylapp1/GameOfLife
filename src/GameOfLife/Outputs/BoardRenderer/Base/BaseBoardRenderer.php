<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use GameOfLife\Board;
use BoardRenderer\Base\Border\BaseBorder;

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

	/**
	 * The height/width of a single field
	 *
	 * @var int $fieldSize
	 */
	protected $fieldSize;


    // Magic Methods

    /**
     * BaseBoardRenderer constructor.
     *
     * @param BaseBorder $_border The border
     * @param BaseBorderRenderer $_borderRenderer The border renderer
     * @param BaseBoardFieldRenderer $_boardFieldRenderer The board field renderer
     * @param BaseCanvas $_canvas The canvas
     * @param int $_fieldSize The height/width of a field in pixels/symbols/etc
     */
    protected function __construct($_border, $_borderRenderer, $_boardFieldRenderer, $_canvas, int $_fieldSize = 1)
    {
	    // TODO: Move this class to BoardRenderer folder
        $this->border = $_border;
        $this->borderRenderer = $_borderRenderer;
        $this->boardFieldRenderer = $_boardFieldRenderer;
        $this->canvas = $_canvas;
        $this->fieldSize = $_fieldSize;
    }


    // Class Methods

    /**
     * Renders a board to the canvas of this board renderer.
     *
     * @param Board $_board The board
     *
     * @return mixed The rendered board content
     */
    public function renderBoard(Board $_board)
    {
        $this->canvas->reset();

        // Render the borders
	    if (! $this->canvas->hasCachedBorderGrid())
	    {
	    	$this->canvas->addBorderGrid($this->borderRenderer->getBorderGrid(), $this->fieldSize);
	    }

        // Render the board fields
	    $renderedBoardFields = $this->boardFieldRenderer->getRenderedBoardFields($_board->fields());
	    $this->canvas->addRenderedBoardFields($renderedBoardFields, $this->fieldSize);

	    return $this->canvas->getContent();
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
