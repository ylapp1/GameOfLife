<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\Border;

use GameOfLife\Board;
use Output\BoardPrinter\Border\InnerBorder\BaseInnerBorder;

/**
 * Parent class for border printers.
 *
 * Call getBorderTopString() and getBorderBottomString() to get the top/bottom border strings
 * Call addBordersToRowString() to add the left/right borders to a single row
 */
abstract class BaseBorder
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

	/**
	 * The width of the bottom and top border
	 *
	 * @var int $borderTopBottomWidth
	 */
	protected $borderTopBottomWidth;

	/**
	 * The list of inner borders of this border
	 *
	 * @var BaseInnerBorder[] $innerBorders
	 */
	protected $innerBorders;


	// Magic Methods

    /**
     * BaseBorderPrinter constructor.
     *
     * @param Board $_board The board to which this border printer belongs
     * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
     * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
     * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
     * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
     * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
     * @param String $_borderSymbolLeftRight The symbol for the left an right border
     */
    protected function __construct(Board $_board, String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
    {
        $this->borderSymbolTopLeft = $_borderSymbolTopLeft;
        $this->borderSymbolTopRight = $_borderSymbolTopRight;
        $this->borderSymbolBottomLeft = $_borderSymbolBottomLeft;
        $this->borderSymbolBottomRight = $_borderSymbolBottomRight;
        $this->borderSymbolTopBottom = $_borderSymbolTopBottom;
        $this->borderSymbolLeftRight = $_borderSymbolLeftRight;

	    $this->borderTopBottomWidth = $_board->width();
	    $this->innerBorders = array();
    }


    // Getters and Setters

	/**
	 * Returns the symbol for the top left corner of the border.
	 *
	 * @return String The symbol for the top left corner of the border
	 */
	public function borderSymbolTopLeft(): String
	{
		return $this->borderSymbolTopLeft;
	}

	/**
	 * Sets the symbol for the top left corner of the border.
	 *
	 * @param String $borderSymbolTopLeft The symbol for the top left corner of the border
	 */
	public function setBorderSymbolTopLeft(String $borderSymbolTopLeft): void
	{
		$this->borderSymbolTopLeft = $borderSymbolTopLeft;
	}

	/**
	 * Returns the symbol for the top right corner of the border.
	 *
	 * @return String The symbol for the top right corner of the border
	 */
	public function borderSymbolTopRight(): String
	{
		return $this->borderSymbolTopRight;
	}

	/**
	 * Sets the symbol for the top right corner of the border.
	 *
	 * @param String $borderSymbolTopRight The symbol for the top right corner of the border
	 */
	public function setBorderSymbolTopRight(String $borderSymbolTopRight): void
	{
		$this->borderSymbolTopRight = $borderSymbolTopRight;
	}

	/**
	 * Returns the symbol for the bottom left corner of the border.
	 *
	 * @return String The symbol for the bottom left corner of the border
	 */
	public function borderSymbolBottomLeft(): String
	{
		return $this->borderSymbolBottomLeft;
	}

	/**
	 * Sets the symbol for the bottom left corner of the border.
	 *
	 * @param String $borderSymbolBottomLeft The symbol for the bottom left corner of the border
	 */
	public function setBorderSymbolBottomLeft(String $borderSymbolBottomLeft): void
	{
		$this->borderSymbolBottomLeft = $borderSymbolBottomLeft;
	}

	/**
	 * Returns the symbol for the bottom right corner of the border.
	 *
	 * @return String The symbol for the bottom right corner of the border
	 */
	public function borderSymbolBottomRight(): String
	{
		return $this->borderSymbolBottomRight;
	}

	/**
	 * Sets the symbol for the bottom right corner of the border.
	 *
	 * @param String $borderSymbolBottomRight The symbol for the bottom right corner of the border
	 */
	public function setBorderSymbolBottomRight(String $borderSymbolBottomRight): void
	{
		$this->borderSymbolBottomRight = $borderSymbolBottomRight;
	}

	/**
	 * Returns the symbol for the top and bottom border.
	 *
	 * @return String The symbol for the top and bottom border
	 */
	public function borderSymbolTopBottom(): String
	{
		return $this->borderSymbolTopBottom;
	}

	/**
	 * Sets the symbol for the top and bottom border.
	 *
	 * @param String $borderSymbolTopBottom The symbol for the top and bottom border
	 */
	public function setBorderSymbolTopBottom(String $borderSymbolTopBottom): void
	{
		$this->borderSymbolTopBottom = $borderSymbolTopBottom;
	}

	/**
	 * Returns the symbol for the left an right border.
	 *
	 * @return String The symbol for the left an right border
	 */
	public function borderSymbolLeftRight(): String
	{
		return $this->borderSymbolLeftRight;
	}

	/**
	 * Sets the symbol for the left an right border.
	 *
	 * @param String $borderSymbolLeftRight The symbol for the left an right border
	 */
	public function setBorderSymbolLeftRight(String $borderSymbolLeftRight): void
	{
		$this->borderSymbolLeftRight = $borderSymbolLeftRight;
	}

	/**
	 * Returns the width of the bottom and top border.
	 *
	 * @return int The width of the bottom and top border
	 */
	public function borderTopBottomWidth(): int
	{
		return $this->borderTopBottomWidth;
	}

	/**
	 * Sets the width of the bottom and top border.
	 *
	 * @param int $borderTopBottomWidth The width of the bottom and top border
	 */
	public function setBorderTopBottomWidth(int $borderTopBottomWidth): void
	{
		$this->borderTopBottomWidth = $borderTopBottomWidth;
	}

	/**
	 * Returns the list of inner borders of this border.
	 *
	 * @return BaseInnerBorder[] The list of inner borders of this border
	 */
	public function innerBorders(): array
	{
		return $this->innerBorders;
	}

	/**
	 * Sets the list of inner borders of this border.
	 *
	 * @param BaseInnerBorder[] $innerBorders The list of inner borders of this border
	 */
	public function setInnerBorders(array $innerBorders): void
	{
		$this->innerBorders = $innerBorders;
	}


	// Class Methods

	/**
	 * Adds an inner border to this border.
	 *
	 * @param BaseInnerBorder $_innerBorder
	 */
	public function addInnerBorder($_innerBorder)
	{
		$this->innerBorders[] = $_innerBorder;
		$_innerBorder->setParentBorder($this);
	}

	/**
	 * Resets the list of inner borders to an empty array.
	 */
	public function resetInnerBorders()
	{
		$this->innerBorders = array();
	}

	/**
	 * Calculates and sets the top and bottom border with.
	 */
    abstract protected function calculateBorderTopBottomWidth();

    /**
     * Returns the string for the top border.
     *
     * @return String The string for the top border
     */
    abstract public function getBorderTopString(): String;

    /**
     * Returns the string for the bottom border.
     *
     * @return String The string for the bottom border
     */
    abstract public function getBorderBottomString(): String;

    /**
     * Adds borders to a row string.
     *
     * @param String $_rowOutputString The row string
     * @param int $_y The Y-Coordinate of the row string
     *
     * @return String The row string with added borders
     */
    abstract public function addBordersToRowString(String $_rowOutputString, int $_y): String;

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
