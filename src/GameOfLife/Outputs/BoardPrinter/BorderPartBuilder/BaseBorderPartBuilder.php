<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\BorderPartBuilder;

use Output\BoardPrinter\BorderPartBuilder\InnerBorderPartBuilder\BaseInnerBorderPartBuilder;
use Output\BoardPrinter\OutputBoard\OutputBoard;

/**
 * Parent class for border printers.
 *
 * Call getBorderTopString() and getBorderBottomString() to get the top/bottom border strings
 * Call addBordersToRowString() to add the left/right borders to a single row
 */
abstract class BaseBorderPartBuilder
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
	 * @var BaseInnerBorderPartBuilder[] $innerBorders
	 */
	protected $innerBorders;


	// Magic Methods

    /**
     * BaseBorderPrinter constructor.
     *
     * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
     * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
     * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
     * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
     * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
     * @param String $_borderSymbolLeftRight The symbol for the left an right border
     */
    protected function __construct(String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
    {
        $this->borderSymbolTopLeft = $_borderSymbolTopLeft;
        $this->borderSymbolTopRight = $_borderSymbolTopRight;
        $this->borderSymbolBottomLeft = $_borderSymbolBottomLeft;
        $this->borderSymbolBottomRight = $_borderSymbolBottomRight;
        $this->borderSymbolTopBottom = $_borderSymbolTopBottom;
        $this->borderSymbolLeftRight = $_borderSymbolLeftRight;

	    $this->borderTopBottomWidth = 0;
	    $this->innerBorders = array();
    }


    // Getters and Setters

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
	public function setBorderTopBottomWidth(int $borderTopBottomWidth)
	{
		$this->borderTopBottomWidth = $borderTopBottomWidth;
	}


	// Class Methods

	/**
	 * Adds an inner border to this border.
	 *
	 * @param BaseInnerBorderPartBuilder $_innerBorder
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

	public function addBordersToOutputBoard(OutputBoard $_outputBoard)
    {
        $this->addBorderTopToOutputBoard($_outputBoard);
        $this->addBorderBottomToOutputBoard($_outputBoard);
        $this->addBorderLeftToOutputBoard($_outputBoard);
        $this->addBorderRightToOutputBoard($_outputBoard);
    }

    /**
     * Adds the top border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    abstract protected function addBorderTopToOutputBoard(OutputBoard $_outputBoard);

    /**
     * Adds the bottom border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    abstract protected function addBorderBottomToOutputBoard(OutputBoard $_outputBoard);

    /**
     * Adds the left border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    abstract protected function addBorderLeftToOutputBoard(OutputBoard $_outputBoard);

    /**
     * Adds the right border of this border to an output board.
     *
     * @param OutputBoard $_outputBoard The output board
     */
    abstract protected function addBorderRightToOutputBoard(OutputBoard $_outputBoard);

    /**
     * Returns a list of symbols for a horizontal or vertical border.
     *
     * @param int $_length The length of the border (not including left and right edge symbol)
     * @param String $_leftEdgeSymbol The symbol for the left edge of the line
     * @param String $_rightEdgeSymbol The symbol for the right edge of the line
     * @param String $_borderSymbol The symbol for the border itself
     *
     * @return String[] The line output symbols
     */
    protected function getBorderSymbolListWithEdgeSymbols(int $_length, String $_leftEdgeSymbol, String $_rightEdgeSymbol, String $_borderSymbol): array
    {
        $borderSymbols = array();

        $borderSymbols[] = $_leftEdgeSymbol;
        $borderSymbols = array_merge($borderSymbols, $this->getBorderSymbolList($_borderSymbol, $_length));
        $borderSymbols[] = $_rightEdgeSymbol;

        return $borderSymbols;
    }

    /**
     * Returns a list of main border symbols.
     *
     * @param String $_borderSymbol The symbol for the border itself
     * @param int $_length The length of the border (not including left and right edge symbol)
     *
     * @return String[] The list of border symbols
     */
    protected function getBorderSymbolList(String $_borderSymbol, int $_length): array
    {
        $borderSymbols = array();
        $borderSymbols = array_pad($borderSymbols, $_length, $_borderSymbol);

        return $borderSymbols;
    }
}
