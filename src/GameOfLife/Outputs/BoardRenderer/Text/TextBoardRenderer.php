<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use BoardRenderer\Base\BaseBoardRenderer;
use BoardRenderer\Base\Border\BaseBorder;

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
     * @param BaseBorder $_border The border of the board
     * @param String $_cellAliveSymbol The symbol that is used to print a living cell
     * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
     */
    protected function __construct($_border, String $_cellAliveSymbol, String $_cellDeadSymbol)
    {
        parent::__construct(
        	$_border,
	        new TextBorderRenderer(),
	        new TextBoardFieldRenderer($_cellAliveSymbol, $_cellDeadSymbol),
	        new TextCanvas()
        );
    }
}
