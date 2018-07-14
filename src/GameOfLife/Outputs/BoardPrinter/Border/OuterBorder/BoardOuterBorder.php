<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\Border\OuterBorder;

use GameOfLife\Board;
use Output\BoardPrinter\OutputBoard;

/**
 * Generates border strings for boards.
 */
class BoardOuterBorder extends BaseOuterBorder
{
    // Magic Methods

    /**
     * BoardOuterBorder constructor.
     *
     * @param Board $_board The board to which the border printer belongs
     */
    public function __construct(Board $_board)
    {
        parent::__construct(
            "╔",
            "╗",
            "╚",
            "╝",
            "═",
            "║",
            $_board
        );

        $this->borderTopBottomWidth = $_board->width();
    }


    // Class Methods

    /**
     * Adds the top border string of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderTopToOutputBoard(OutputBoard $_outputBoard)
    {
        $borderSymbols = $this->getBorderSymbolListWithEdgeSymbols(
        	$this->borderTopBottomWidth, $this->borderSymbolTopLeft, $this->borderSymbolTopRight, $this->borderSymbolTopBottom
        );
        $_outputBoard->addOuterBorderTop($borderSymbols);
    }

    /**
     * Adds the bottom border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderBottomToOutputBoard(OutputBoard $_outputBoard)
    {
        $borderSymbols = $this->getBorderSymbolListWithEdgeSymbols(
            $this->borderTopBottomWidth, $this->borderSymbolBottomLeft, $this->borderSymbolBottomRight, $this->borderSymbolTopBottom
        );
        $_outputBoard->addOuterBorderBottom($borderSymbols);
    }

    /**
     * Adds the left border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderLeftToOutputBoard(OutputBoard $_outputBoard)
    {
        $borderSymbols = $this->getBorderSymbolList($this->borderSymbolLeftRight, $this->board->height());
        $_outputBoard->addOuterBorderLeft($borderSymbols);
    }

    /**
     * Adds the right border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderRightToOutputBoard(OutputBoard $_outputBoard)
    {
        $borderSymbols = $this->getBorderSymbolList($this->borderSymbolLeftRight, $this->board->height());
        $_outputBoard->addOuterBorderRight($borderSymbols);
    }
}
