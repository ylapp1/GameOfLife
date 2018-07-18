<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\Border\OuterBorder;

use GameOfLife\Board;
use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard;
use Output\BoardPrinter\OutputBoard\HorizontalOutputBorder;
use Output\BoardPrinter\OutputBoard\VerticalOutputBorder;

/**
 * Generates border strings for boards.
 */
class BoardOuterBorderPartBuilde extends BaseOuterBorderPartBuilder
{
    // Magic Methods

    /**
     * BoardOuterBorderPartBuilde constructor.
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
        $border = new HorizontalOutputBorder(new Coordinate(0, 0), new Coordinate($this->board->width(), 0), $borderSymbols);

        $_outputBoard->addBorder($border);
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
	    $border = new HorizontalOutputBorder(new Coordinate(0, $this->board->height()), new Coordinate($this->board->width(), $this->board->height()), $borderSymbols);

	    $_outputBoard->addBorder($border);
    }

    /**
     * Adds the left border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderLeftToOutputBoard(OutputBoard $_outputBoard)
    {
        $borderSymbols = $this->getBorderSymbolList($this->borderSymbolLeftRight, $this->board->height());
        $border = new VerticalOutputBorder(new Coordinate(0, 0), new Coordinate(0, $this->board->height()), $borderSymbols);

        $_outputBoard->addBorder($border);
    }

    /**
     * Adds the right border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderRightToOutputBoard(OutputBoard $_outputBoard)
    {
        $borderSymbols = $this->getBorderSymbolList($this->borderSymbolLeftRight, $this->board->height());
        $border = new VerticalOutputBorder(new Coordinate($this->board->width(), 0), new Coordinate($this->board->width(), $this->board->height()), $borderSymbols);

        $_outputBoard->addBorder($border);
    }
}
