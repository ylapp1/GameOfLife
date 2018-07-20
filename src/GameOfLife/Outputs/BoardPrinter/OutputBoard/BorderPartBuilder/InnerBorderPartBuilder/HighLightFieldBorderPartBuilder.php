<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\BorderPartBuilder\InnerBorderPartBuilder;

use GameOfLife\Board;
use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBoard;

/**
 * Prints the borders for a high light field.
 */
class HighLightFieldBorderPartBuilder extends BaseInnerBorderPartBuilder
{
    // Magic Methods

    /**
     * HighLightFieldBorderPartBuilder constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "┼",
            "┼",
            "┼",
            "┼",
            "─",
            "│",
            "╤",
            "╧",
            "╟",
            "╢"
        );
    }


    // Class Methods

    /**
     * Initializes the border printer.
     * This method must be called before using any of the inherited methods.
     *
     * @param Board $_board The board
     * @param Coordinate $_highLightFieldCoordinate The high light field coordinate
     */
    public function initialize(Board $_board, Coordinate $_highLightFieldCoordinate)
    {
        $this->init($_board, $_highLightFieldCoordinate, $_highLightFieldCoordinate);
    }

    /**
     * Adds collision borders to the top outer border.
     * Also adds the X-Coordinate string above the outer border.
     *
     * @param String $_topOuterBorderString The top outer border string
     *
     * @return String The X-Coordinate string and the adjusted top outer border string
     */
    public function addCollisionBorderToTopOuterBorder(String $_topOuterBorderString): String
    {
    	/*
        $topOuterBorderString = parent::addCollisionBorderToLeftOuterBorder($_topOuterBorderString);

        // TODO: Need board as parameter for this ...
        $xCoordinateHighLightString = $this->getXCoordinateHighLightString($_board);
        return $xCoordinateHighLightString . "\n" . $topOuterBorderString;
    	*/
    }

    private function getXCoordinateHighLightString(Board $_board)
    {
        // TODO: Need to save border positions for each border individually
        $hasInnerLeftBorder = $this->hasLeftBorder();
        $hasInnerRightBorder = $this->hasRightBorder();

        $paddingLeftLength = $this->highLightFieldCoordinate->x() + (int)$hasInnerLeftBorder;
        $paddingTotalLength = $_board->width() + (int)$hasInnerLeftBorder + (int)$hasInnerRightBorder;

        // Output the X-Coordinate of the highlighted cell above the board
        $paddingLeftString = str_repeat(" ", $paddingLeftLength);
        $xCoordinateHighLightString = str_pad(
            $paddingLeftString . $this->highLightFieldCoordinate->x(),
            $paddingTotalLength
        );

        return $xCoordinateHighLightString . "\n";
    }

    public function addBordersToRowString(String $_rowOutputString, int $_y): String
    {
    	$rowOutputString = $_rowOutputString;

	    if ($this->highLightFieldCoordinate && $_y == $this->highLightFieldCoordinate->y())
	    {
		    $rowOutputString .= " " . $_y;
	    }

	    return parent::addBordersToRowString($rowOutputString, $_y);
    }

	/**
	 * Adds the top border of this border to an output board.
	 *
	 * @param OutputBoard $_outputBoard The output board
	 */
	protected function addBorderTopToOutputBoard(OutputBoard $_outputBoard)
	{
		// TODO: Implement addBorderTopToOutputBoard() method.
	}

	/**
	 * Adds the bottom border of this border to an output board.
	 *
	 * @param OutputBoard $_outputBoard The output board
	 */
	protected function addBorderBottomToOutputBoard(OutputBoard $_outputBoard)
	{
		// TODO: Implement addBorderBottomToOutputBoard() method.
	}

	/**
	 * Adds the left border of this border to an output board.
	 *
	 * @param OutputBoard $_outputBoard The output board
	 */
	protected function addBorderLeftToOutputBoard(OutputBoard $_outputBoard)
	{
		// TODO: Implement addBorderLeftToOutputBoard() method.
	}

	/**
	 * Adds the right border of this border to an output board.
	 *
	 * @param OutputBoard $_outputBoard The output board
	 */
	protected function addBorderRightToOutputBoard(OutputBoard $_outputBoard)
	{
		// TODO: Implement addBorderRightToOutputBoard() method.
	}
}
