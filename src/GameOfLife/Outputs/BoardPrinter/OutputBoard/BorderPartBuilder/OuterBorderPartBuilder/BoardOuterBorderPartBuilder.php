<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\BorderPartBuilder\OuterBorderPartBuilder;

use GameOfLife\Board;
use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBoard;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\HorizontalOutputBorderPart;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\VerticalOutputBorderPart;

/**
 * Generates border strings for boards.
 */
class BoardOuterBorderPartBuilder extends BaseOuterBorderPartBuilder
{
    // Magic Methods

    /**
     * BoardOuterBorderPartBuilder constructor.
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
    }


    // Class Methods

    /**
     * Adds the top border string of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderTopToOutputBoard(OutputBoard $_outputBoard)
    {
	    // TODO: Think of outer/inner collision concept

	    $border = new HorizontalOutputBorderPart(
		    new Coordinate(0, 0),
		    new Coordinate($this->board->width(), 0),
		    $this->borderSymbolTopLeft,
		    $this->borderSymbolTopBottom,
		    $this->borderSymbolTopRight,
		    $this->borderSymbolTopLeft,
		    "X",
		    $this->borderSymbolTopRight,
		    $this->borderSymbolTopLeft,
		    "X",
		    $this->borderSymbolTopRight
	    );

        $_outputBoard->addBorderPart($border);
    }

    /**
     * Adds the bottom border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderBottomToOutputBoard(OutputBoard $_outputBoard)
    {
	    // TODO: Think of outer/inner collision concept

	    $border = new HorizontalOutputBorderPart(
		    new Coordinate(0, $this->board->height()),
		    new Coordinate($this->board->width(), $this->board->height()),
		    $this->borderSymbolBottomLeft,
		    $this->borderSymbolTopBottom,
		    $this->borderSymbolBottomRight,
		    $this->borderSymbolBottomLeft,
		    "X",
		    $this->borderSymbolBottomRight,
		    $this->borderSymbolBottomLeft,
		    "X",
		    $this->borderSymbolBottomRight
	    );

	    $_outputBoard->addBorderPart($border);
    }

    /**
     * Adds the left border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderLeftToOutputBoard(OutputBoard $_outputBoard)
    {
    	// TODO: Think of outer/inner collision concept

	    $border = new VerticalOutputBorderPart(
		    new Coordinate(0, 0),
		    new Coordinate(0, $this->board->height()),
		    $this->borderSymbolTopLeft,
		    $this->borderSymbolLeftRight,
		    $this->borderSymbolBottomLeft,
		    $this->borderSymbolTopLeft,
		    "X",
		    $this->borderSymbolBottomLeft,
		    $this->borderSymbolTopLeft,
		    "X",
		    $this->borderSymbolBottomLeft
	    );

        $_outputBoard->addBorderPart($border);
    }

    /**
     * Adds the right border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    protected function addBorderRightToOutputBoard(OutputBoard $_outputBoard)
    {
    	// TODO: Think of outer/inner collision concept

        $border = new VerticalOutputBorderPart(
        	new Coordinate($this->board->width() + 1, 0),
	        new Coordinate($this->board->width() + 1, $this->board->height()),
	        $this->borderSymbolTopRight,
	        $this->borderSymbolLeftRight,
	        $this->borderSymbolBottomRight,
	        $this->borderSymbolTopRight,
	        "X",
	        $this->borderSymbolBottomRight,
	        $this->borderSymbolTopRight,
	        "X",
	        $this->borderSymbolBottomRight
        );

        $_outputBoard->addBorderPart($border);
    }
}
