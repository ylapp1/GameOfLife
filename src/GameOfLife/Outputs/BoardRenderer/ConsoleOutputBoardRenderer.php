<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer;

use GameOfLife\Board;
use Output\BoardRenderer\Base\BaseBoardRenderer;
use Output\BoardRenderer\Text\Border\BoardOuterBorder;
use Output\BoardRenderer\Text\TextBoardFieldRenderer;
use Output\BoardRenderer\Text\TextBorderRenderer;
use Output\BoardRenderer\Text\TextCanvas;

/**
 * The BoardRenderer for the ConsoleOutput.
 */
class ConsoleOutputBoardRenderer extends BaseBoardRenderer
{
	// Magic Methods

    /**
     * ConsoleOutputBoardRenderer constructor.
     *
     * @param Board $_board The board to which this board printer belongs
     */
    public function __construct(Board $_board)
    {
        parent::__construct(
        	new BoardOuterBorder($_board),
        	new TextBorderRenderer(),
        	new TextBoardFieldRenderer("☻", " "),
	        new TextCanvas()
        );
    }
}
