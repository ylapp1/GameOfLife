<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\Border\OuterBorder;

use GameOfLife\Board;
use Output\BoardPrinter\Border\BaseBorder;

/**
 * Parent class for outer border printers.
 */
abstract class BaseOuterBorder extends BaseBorder
{
	// Attributes

	/**
	 * The board to which this outer border printer belongs
	 *
	 * @var Board $board
	 */
	private $board;


	// Magic Methods

	/**
	 * BaseOuterBorderPrinter constructor.
	 *
	 * @param Board $_board The board to which this border printer belongs
	 * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
	 * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
	 * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
	 * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
	 * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
	 * @param String $_borderSymbolLeftRight The symbol for the left an right border
	 */
	public function __construct(Board $_board, String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
	{
		parent::__construct($_board, $_borderSymbolTopLeft, $_borderSymbolTopRight, $_borderSymbolBottomLeft, $_borderSymbolBottomRight, $_borderSymbolTopBottom, $_borderSymbolLeftRight);

		$this->board = $_board;
	}


	// Getters and Setters

	/**
	 * Returns the board to which this outer border printer belongs.
	 *
	 * @return Board The board to which this outer border printer belongs
	 */
	public function board(): Board
	{
		return $this->board;
	}

	/**
	 * Sets the board to which this outer border printer belongs.
	 *
	 * @param Board $board The board to which this outer border printer belongs
	 */
	public function setBoard(Board $board): void
	{
		$this->board = $board;
	}


	// Class Methods

	/**
	 * Calculates and sets the top and bottom border with based on the inner borders of this border.
	 */
	protected function calculateBorderTopBottomWidth()
	{
		$borderLeftRightPositions = array();

		foreach ($this->innerBorders as $innerBorder)
		{
			$borderLeftRightPositions[] = $innerBorder->hasLeftBorder();
			$borderLeftRightPositions[] = $innerBorder->hasRightBorder();
		}

		$borderLeftRightPositions = array_unique($borderLeftRightPositions);

		$numberOfInnerBorders = 0;
		foreach ($borderLeftRightPositions as $borderLeftRightPosition)
		{
			if ($borderLeftRightPositions > 0 && $borderLeftRightPosition < $this->board->width())
			{
				$numberOfInnerBorders++;
			}
		}

		$this->borderTopBottomWidth = $this->board->width() + $numberOfInnerBorders;
	}
}
