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

abstract class BaseBoardRenderer
{
    /**
     * The border and its inner borders
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
     * The cached rendered border symbol grid
     *
     * @var BaseSymbolGrid $borderSymbolGrid
     */
    protected $borderSymbolGrid;

    /**
     * The cached board field symbol grid
     *
     * @var BaseSymbolGrid $boardFieldSymbolGrid
     */
    protected $boardFieldSymbolGrid;

    /**
     * The combination of border and board field symbol grid
     *
     * @var BaseSymbolGrid $boardSymbolGrid
     */
    protected $boardSymbolGrid;


    /**
     * Resets the symbol and border symbol grid.
     */
    public function reset()
    {
        $this->boardFieldRenderer->reset();
        $this->borderRenderer->resetBorders();
    }

    public function renderBoard(Board $_board)
    {
        // Render the borders
        $this->border->addBorderPartsToBorderRenderer($this->borderRenderer);
        $this->borderRenderer->renderBorderParts();

        // Render the board fields
        $this->boardFieldRenderer->renderBoardFields($_board->fields());

        // Merge the border and board field symbol grids
        // TODO
    }

    abstract public function getBoardContent();
}
