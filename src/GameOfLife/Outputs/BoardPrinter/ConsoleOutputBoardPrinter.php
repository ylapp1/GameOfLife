<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter;

use GameOfLife\Board;
use Output\BoardPrinter\Border\OuterBorder\BoardOuterBorder;

/**
 * The BoardPrinter for the ConsoleOutput.
 */
class ConsoleOutputBoardPrinter extends BaseBoardPrinter
{
	// Magic Methods

    /**
     * ConsoleOutputBoardPrinter constructor.
     *
     * @param Board $_board The board to which this board printer belongs
     */
    public function __construct(Board $_board)
    {
        parent::__construct("☻", " ", new BoardOuterBorder($_board));
    }
}
