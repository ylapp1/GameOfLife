<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer;

use GameOfLife\Board;
use BoardRenderer\Base\BaseBoardRenderer;
use BoardRenderer\Text\Border\BoardOuterBorder;
use BoardRenderer\Text\TextBoardFieldRenderer;
use BoardRenderer\Text\TextBorderRenderer;
use BoardRenderer\Text\TextCanvas;

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
    	$border = new BoardOuterBorder($_board);

        parent::__construct(
        	new BoardOuterBorder($_board),
        	new TextBorderRenderer($_board, $border),
        	new TextBoardFieldRenderer("☻", " "),
	        new TextCanvas()
        );
    }
}
