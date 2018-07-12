<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\BorderPrinter;

use GameOfLife\Board;

/**
 * Generates border strings for boards.
 */
class BoardBorderPrinter extends BaseBorderPrinter
{
    // Magic Methods

    /**
     * BoardBorderPrinter constructor.
     *
     * @param Board $_board The board to which the border printer belongs
     */
    public function __construct(Board $_board)
    {
        parent::__construct(
        	$_board,
            "╔",
            "╗",
            "╚",
            "╝",
            "═",
            "║"
        );
    }


    // Class Methods

    /**
     * Returns the string for the top border.
     *
     * @return String The string for the top border
     */
    public function getBorderTopString(): String
    {
        return $this->getHorizontalLineString(
        	$this->borderTopBottomWidth, $this->borderSymbolTopLeft, $this->borderSymbolTopRight, $this->borderSymbolTopBottom
        );
    }

    /**
     * Returns the string for the bottom border.
     *
     * @return String The string for the bottom border
     */
    public function getBorderBottomString(): String
    {
        return $this->getHorizontalLineString(
        	$this->borderTopBottomWidth, $this->borderSymbolBottomLeft, $this->borderSymbolBottomRight, $this->borderSymbolTopBottom
        );
    }

    /**
     * Adds borders to a row string.
     *
     * @param String $_rowString The row string
     * @param int $_y The Y-Coordinate of the row string
     *
     * @return String The row string with added borders
     */
    public function addBordersToRowString(String $_rowString, int $_y): String
    {
        return $this->borderSymbolLeftRight . $_rowString . $this->borderSymbolLeftRight;
    }
}
