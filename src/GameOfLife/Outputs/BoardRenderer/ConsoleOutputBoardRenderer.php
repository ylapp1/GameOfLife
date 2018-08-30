<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer;

use BoardRenderer\Text\Border\TextBoardOuterBorder;
use BoardRenderer\Text\TextBoardFieldRenderer;
use BoardRenderer\Text\TextBorderGridBuilder;
use BoardRenderer\Text\TextCanvas;
use GameOfLife\Board;

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
    	$border = new TextBoardOuterBorder($_board);

        parent::__construct(
        	new TextBorderGridBuilder($_board, $border, false),
	        $border,
        	new TextBoardFieldRenderer("☻", " "),
	        new TextCanvas()
        );
    }
}
