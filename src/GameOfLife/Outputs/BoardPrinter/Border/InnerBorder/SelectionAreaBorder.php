<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\Border\InnerBorder;

use BoardEditor\SelectionArea;
use GameOfLife\Board;

/**
 * Prints the borders for selection areas inside boards.
 */
class SelectionAreaBorder extends BaseInnerBorder
{
    // Magic Methods

    /**
     * SelectionAreaBorder constructor.
     *
     * @param Board $_board The board to which this border printer belongs
     */
    public function __construct(Board $_board)
    {
        parent::__construct(
	        $_board,
            "┏",
            "┓",
            "┗",
	        "┛",
            "╍",
            "┋",
            "╤",
            "╧",
            "╟",
            "╢"
        );
    }

    /**
     * Initializes the border printer.
     * This method must be called before using any of the inherited methods.
     *
     * @param Board $_board The board
     * @param SelectionArea $_selectionArea The selection area
     */
    public function initialize(Board $_board, SelectionArea $_selectionArea)
    {
        $this->init($_board, $_selectionArea->topLeftCornerCoordinate(), $_selectionArea->bottomRightCornerCoordinate());
    }
}
