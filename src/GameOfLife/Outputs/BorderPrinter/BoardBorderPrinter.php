<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BorderPrinter;

use GameOfLife\Board;

/**
 * Generates border strings for boards.
 */
class BoardBorderPrinter extends BaseBorderPrinter
{
    // Attributes

    /**
     * The cached top border
     *
     * @var String $cachedTopBorder
     */
    private $cachedTopBorder;

    /**
     * The cached bottom border
     *
     * @var String $cachedBottomBorder
     */
    private $cachedBottomBorder;


    // Magic Methods

    /**
     * BoardBorderPrinter constructor.
     */
    public function __construct()
    {
        parent::__construct(
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
     * @param Board $_board The board
     *
     * @return String The string for the top border
     */
    public function getBorderTopString(Board $_board): String
    {
        if (! $this->cachedTopBorder)
        {
            $this->cachedTopBorder = $this->getHorizontalLineString(
                $_board->width(), $this->borderSymbolTopLeft, $this->borderSymbolTopRight, $this->borderSymbolTopBottom
            );
        }

        return $this->cachedTopBorder;
    }

    /**
     * Returns the string for the bottom border.
     *
     * @param Board $_board The board
     *
     * @return String The string for the bottom border
     */
    public function getBorderBottomString(Board $_board): String
    {
        if (! $this->cachedBottomBorder)
        {
            $this->cachedBottomBorder = $this->getHorizontalLineString(
                $_board->width(), $this->borderSymbolBottomLeft, $this->borderSymbolBottomRight, $this->borderSymbolTopBottom
            );
        }

        return $this->cachedBottomBorder;
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
