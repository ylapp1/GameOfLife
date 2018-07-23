<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;

use Output\BoardRenderer\Base\BaseBoardRenderer;

/**
 * Renders a board to text symbols.
 *
 * Call renderBoard() to render a board
 * Call getContent() to get the rendered board
 */
class TextBoardRenderer extends BaseBoardRenderer
{
    /**
     * TextBoardRenderer constructor.
     *
     * @param String $_cellAliveSymbol The symbol that is used to print a living cell
     * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
     */
    protected function __construct(String $_cellAliveSymbol, String $_cellDeadSymbol)
    {
        $this->canvas = new TextCanvas();
        $this->borderRenderer = new TextBorderRenderer();
        $this->boardFieldRenderer = new TextBoardFieldRenderer($_cellAliveSymbol, $_cellDeadSymbol);
    }
}
