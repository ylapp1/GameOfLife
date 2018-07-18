<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\Border\OuterBorder;

use GameOfLife\Board;
use Output\BoardPrinter\Border\BaseBorderPartBuilder;

/**
 * Parent class for outer borders.
 * Outer borders may not overlap.
 */
abstract class BaseOuterBorderPartBuilder extends BaseBorderPartBuilder
{
	// Attributes

	/**
	 * The board to which this outer border printer belongs
	 *
	 * @var Board $board
	 */
	protected $board;


	// Magic Methods

	/**
	 * BaseOuterBorderPrinter constructor.
	 *
	 * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
	 * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
	 * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
	 * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
	 * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
	 * @param String $_borderSymbolLeftRight The symbol for the left an right border
     * @param Board $_board The board to which this border printer belongs
     */
	public function __construct(String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight, Board $_board)
	{
		parent::__construct($_borderSymbolTopLeft, $_borderSymbolTopRight, $_borderSymbolBottomLeft, $_borderSymbolBottomRight, $_borderSymbolTopBottom, $_borderSymbolLeftRight);

		$this->board = $_board;
	}


	// Getters and Setters

	public function addInnerBorder($_innerBorder)
	{
		parent::addInnerBorder($_innerBorder);
		$this->calculateBorderTopBottomWidth();
	}


	// Class Methods

	/**
	 * Calculates and sets the top and bottom border with based on the inner borders of this border.
	 */
	protected function calculateBorderTopBottomWidth()
	{
		$this->borderTopBottomWidth = $this->board->width() + count($this->getInnerBorderLeftRightPositions());
	}

    /**
     * Returns all left and right border positions of all inner borders.
     *
     * @return array
     */
	public function getInnerBorderLeftRightPositions()
    {
        $innerBorderLeftRightPositions = array();

        foreach ($this->innerBorders as $innerBorder)
        {
            if ($innerBorder->hasLeftBorder() && $innerBorder->hasLeftBorder() < $this->board->width())
            {
                $innerBorderLeftRightPositions[] = $innerBorder->hasLeftBorder();
            }
            if ($innerBorder->hasRightBorder() && $innerBorder->hasRightBorder() < $this->board->width())
            {
                $innerBorderLeftRightPositions[] = $innerBorder->hasRightBorder();
            }
        }

        $innerBorderLeftRightPositions = array_unique($innerBorderLeftRightPositions);

        return $innerBorderLeftRightPositions;
    }
}
