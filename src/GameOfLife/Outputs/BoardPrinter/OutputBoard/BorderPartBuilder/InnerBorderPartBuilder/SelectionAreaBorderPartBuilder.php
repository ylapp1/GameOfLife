<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\BorderPartBuilder\InnerBorderPartBuilder;

use BoardEditor\SelectionArea;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBoard;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\HorizontalOutputBorderPart;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\VerticalOutputBorderPart;

/**
 * Prints the borders for selection areas inside boards.
 */
class SelectionAreaBorderPartBuilder extends BaseInnerBorderPartBuilder
{
    // Magic Methods

    /**
     * SelectionAreaBorderPartBuilder constructor.
     */
    public function __construct()
    {
        parent::__construct(
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

	/**
	 * Adds the top border of this border to an output board.
	 *
	 * @param OutputBoard $_outputBoard The output board
	 */
	protected function addBorderTopToOutputBoard(OutputBoard $_outputBoard)
	{
		$border = new HorizontalOutputBorderPart(
			new Coordinate($this->topLeftCornerCoordinate->x(), $this->topLeftCornerCoordinate->y()),
			new Coordinate($this->bottomRightCornerCoordinate->x(), $this->topLeftCornerCoordinate->y()),
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
		$border = new HorizontalOutputBorderPart(
			new Coordinate($this->topLeftCornerCoordinate->x(), $this->bottomRightCornerCoordinate->y()),
			new Coordinate($this->bottomRightCornerCoordinate->x(), $this->bottomRightCornerCoordinate->y()),
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
		$border = new VerticalOutputBorderPart(
			new Coordinate($this->topLeftCornerCoordinate->x(), $this->topLeftCornerCoordinate->y()),
			new Coordinate($this->topLeftCornerCoordinate->x(), $this->bottomRightCornerCoordinate->y()),
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
		$border = new VerticalOutputBorderPart(
			new Coordinate($this->bottomRightCornerCoordinate->x(), $this->topLeftCornerCoordinate->y()),
			new Coordinate($this->bottomRightCornerCoordinate->x(), $this->bottomRightCornerCoordinate->y()),
			$this->borderSymbolTopRight,
			$this->borderSymbolLeftRight,
			$this->borderSymbolBottomRight,
			$this->borderSymbolCollisionRightOuterBorder,
			"X",
			$this->borderSymbolCollisionRightOuterBorder,
			$this->borderSymbolTopRight,
			"X",
			$this->borderSymbolBottomRight
		);

		$_outputBoard->addBorderPart($border);
	}
}
