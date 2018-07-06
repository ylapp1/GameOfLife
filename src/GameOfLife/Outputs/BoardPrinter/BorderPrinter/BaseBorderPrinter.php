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
 * Parent class for border printers.
 *
 * Call getBorderTopString() and getBorderBottomString() to get the top/bottom border strings
 * Call addBordersToRowString() to add the left/right borders to a single row
 */
abstract class BaseBorderPrinter
{
    // Attributes

    /**
     * The symbol for the top left corner of the border
     *
     * @var String $borderSymbolTopLeft
     */
    protected $borderSymbolTopLeft;

    /**
     * The symbol for the top right corner of the border
     *
     * @var String $borderSymbolTopRight
     */
    protected $borderSymbolTopRight;

    /**
     * The symbol for the bottom left corner of the border
     *
     * @var String $borderSymbolBottomLeft
     */
    protected $borderSymbolBottomLeft;

    /**
     * The symbol for the bottom right corner of the border
     *
     * @var String $borderSymbolBottomRight
     */
    protected $borderSymbolBottomRight;

    /**
     * The symbol for the top and bottom border
     *
     * @var String $borderSymbolTopBottom
     */
    protected $borderSymbolTopBottom;

    /**
     * The symbol for the left an right border
     *
     * @var String $borderSymbolLeftRight
     */
    protected $borderSymbolLeftRight;


    // Magic Methods

    /**
     * BaseBorderPrinter constructor.
     *
     * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
     * @param String $_borderSymbolTopRight
     * @param String $_borderSymbolBottomLeft
     * @param String $_borderSymbolBottomRight
     * @param String $_borderSymbolTopBottom
     * @param String $_borderSymbolLeftRight
     */
    protected function __construct(String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
    {
        $this->borderSymbolTopLeft = $_borderSymbolTopLeft;
        $this->borderSymbolTopRight = $_borderSymbolTopRight;
        $this->borderSymbolBottomLeft = $_borderSymbolBottomLeft;
        $this->borderSymbolBottomRight = $_borderSymbolBottomRight;
        $this->borderSymbolTopBottom = $_borderSymbolTopBottom;
        $this->borderSymbolLeftRight = $_borderSymbolLeftRight;
    }


    // Class Methods

    /**
     * Returns the string for the top border.
     *
     * @param Board $_board The board
     *
     * @return String The string for the top border
     */
    abstract public function getBorderTopString(Board $_board): String;

    /**
     * Returns the string for the bottom border.
     *
     * @param Board $_board The board
     *
     * @return String The string for the bottom border
     */
    abstract public function getBorderBottomString(Board $_board): String;

    /**
     * Adds borders to a row string.
     *
     * @param String $_rowString The row string
     * @param int $_y The Y-Coordinate of the row string
     *
     * @return String The row string with added borders
     */
    abstract public function addBordersToRowString(String $_rowString, int $_y): String;

    /**
     * Returns a horizontal line string.
     *
     * @param int $_length The length of the line (not including left and right edge symbol)
     * @param String $_leftEdgeSymbol The symbol for the left edge of the line
     * @param String $_rightEdgeSymbol The symbol for the right edge of the line
     * @param String $_lineSymbol The symbol for the line itself
     *
     * @return String The line output string
     */
    protected function getHorizontalLineString(int $_length, String $_leftEdgeSymbol, String $_rightEdgeSymbol, String $_lineSymbol): String
    {
        return $_leftEdgeSymbol . str_repeat($_lineSymbol, $_length) . $_rightEdgeSymbol;
    }
}
