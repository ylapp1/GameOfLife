<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\BorderPrinter;

use GameOfLife\Board;
use GameOfLife\Coordinate;

/**
 * Parent class for inner border printers.
 * Inner Borders are the same as normal borders with the addition that they may touch an outer border with which they will merge
 */
abstract class BaseInnerBorderPrinter extends BaseBorderPrinter
{
	// Attributes

	/**
	 * The symbol that will be placed in the top outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionTopOuterBorder
	 */
    private $borderSymbolCollisionTopOuterBorder;

	/**
	 * The symbol that will be placed in the bottom outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionBottomOuterBorder
	 */
    private $borderSymbolCollisionBottomOuterBorder;

	/**
	 * The symbol that will be placed in the left outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionLeftOuterBorder
	 */
    private $borderSymbolCollisionLeftOuterBorder;

	/**
	 * The symbol that will be placed in the right outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionRightOuterBorder
	 */
    private $borderSymbolCollisionRightOuterBorder;


    protected $distanceToTopOuterBorder;
    protected $distanceToBottomOuterBorder;
    protected $distanceToLeftOuterBorder;
    protected $distanceToRightOuterBorder;

	protected $borderSymbolPositionsTopBottom;
	protected $borderSymbolPositionsLeftRight;

    /**
     * @var Coordinate $topLeftCornerCoordinate
     */
    protected $topLeftCornerCoordinate;

    /**
     * @var Coordinate $bottomRightCornerCoordinate
     */
    protected $bottomRightCornerCoordinate;



    // Magic Methods

	/**
	 * BaseInnerBorderPrinter constructor.
	 *
	 * @param Board $_board The board to which this border printer belongs
	 * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
	 * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
	 * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
	 * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
	 * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
	 * @param String $_borderSymbolLeftRight The symbol for the left an right border
	 * @param String $_borderSymbolCollisionTopOuterBorder The symbol that will be placed in the top outer border when this border collides with it
	 * @param String $_borderSymbolCollisionBottomOuterBorder The symbol that will be placed in the bottom outer border when this border collides with it
	 * @param String $_borderSymbolCollisionLeftOuterBorder The symbol that will be placed in the left outer border when this border collides with it
	 * @param String $_borderSymbolCollisionRightOuterBorder The symbol that will be placed in the right outer border when this border collides with it
	 */
    protected function __construct(Board $_board, String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight, String $_borderSymbolCollisionTopOuterBorder, String $_borderSymbolCollisionBottomOuterBorder, String $_borderSymbolCollisionLeftOuterBorder, String $_borderSymbolCollisionRightOuterBorder)
    {
        parent::__construct($_board, $_borderSymbolTopLeft, $_borderSymbolTopRight, $_borderSymbolBottomLeft, $_borderSymbolBottomRight, $_borderSymbolTopBottom, $_borderSymbolLeftRight);

        $this->borderSymbolCollisionTopOuterBorder = $_borderSymbolCollisionTopOuterBorder;
        $this->borderSymbolCollisionBottomOuterBorder = $_borderSymbolCollisionBottomOuterBorder;
        $this->borderSymbolCollisionLeftOuterBorder = $_borderSymbolCollisionLeftOuterBorder;
        $this->borderSymbolCollisionRightOuterBorder = $_borderSymbolCollisionRightOuterBorder;
    }


    // Class Methods

    protected function init(Board $_board, Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate)
    {
        $this->distanceToTopOuterBorder = $_topLeftCornerCoordinate->y();
        $this->distanceToBottomOuterBorder = ($_board->height() - 1) - $_bottomRightCornerCoordinate->y();
        $this->distanceToLeftOuterBorder = $_topLeftCornerCoordinate->x();
        $this->distanceToRightOuterBorder = ($_board->width() - 1) - $_bottomRightCornerCoordinate->x();

        $this->topLeftCornerCoordinate = $_topLeftCornerCoordinate;
        $this->bottomRightCornerCoordinate = $_bottomRightCornerCoordinate;

        $this->borderSymbolPositionsTopBottom = array();
        if ($this->distanceToTopOuterBorder == 0) $this->borderSymbolPositionsTopBottom[] = $_topLeftCornerCoordinate->y();
        if ($this->distanceToBottomOuterBorder == 0)
        {
            $this->borderSymbolPositionsTopBottom[] = $_bottomRightCornerCoordinate->y() + (int)$this->hasTopBorder() + 1;
        }

        $this->borderSymbolPositionsLeftRight = array();
        if ($this->distanceToLeftOuterBorder == 0) $this->borderSymbolPositionsLeftRight[] = $_topLeftCornerCoordinate->x();
        if ($this->distanceToRightOuterBorder == 0)
        {
            $this->borderSymbolPositionsLeftRight[] = $_bottomRightCornerCoordinate->x() + (int)$this->hasLeftBorder() + 1;
        }

        $this->borderTopBottomWidth = ($_board->width() - $this->distanceToBottomOuterBorder - $this->distanceToRightOuterBorder) - (int)$this->hasLeftBorder() - (int)$this->hasRightBorder();
    }


    public function hasTopBorder()
    {
        if ($this->distanceToTopOuterBorder == 0) return false;
        else return true;
    }

    public function hasBottomBorder()
    {
        if ($this->distanceToBottomOuterBorder == 0) return false;
        else return true;
    }

    public function hasLeftBorder()
    {
        if ($this->distanceToLeftOuterBorder == 0) return false;
        else return true;
    }

    public function hasRightBorder()
    {
        if ($this->distanceToRightOuterBorder == 0) return false;
        else return true;
    }


	/**
	 * Returns the string for the top border.
	 *
	 * @return String The string for the top border
	 */
    public function getBorderTopString(): String
    {
        return $this->getBorderTopBottomString($this->borderSymbolTopLeft, $this->borderSymbolTopRight);
    }

	/**
	 * Returns the string for the bottom border.
	 *
	 * @return String The string for the bottom border
	 */
    public function getBorderBottomString(): String
    {
        return $this->getBorderTopBottomString($this->borderSymbolBottomLeft, $this->borderSymbolBottomRight);
    }

	/**
	 * Returns a string for either the top or bottom border of this inner border.
	 * The bottom and top borders only differ in the left and right edge symbols.
	 *
	 * @param String $_borderLeftSymbol The symbol for the left edge of the inner border when the border does not collide with the outer border
	 * @param String $_borderRightSymbol The symbol for the right edge of the inner border when the border does not collide with the outer border
	 *
	 * @return String The top or bottom border string of this inner border
	 */
    private function getBorderTopBottomString(String $_borderLeftSymbol, String $_borderRightSymbol): String
    {
        if ($this->hasLeftBorder()) $borderLeftSymbol = $_borderLeftSymbol;
        else $borderLeftSymbol = $this->borderSymbolTopBottom;

        if ($this->hasRightBorder()) $borderRightSymbol = $_borderRightSymbol;
        else $borderRightSymbol = $this->borderSymbolTopBottom;

        $borderTopBottomString = $this->getHorizontalLineString(
            $this->borderTopBottomWidth, $borderLeftSymbol, $borderRightSymbol, $this->borderSymbolTopBottom
        );

        $paddingLeftString = str_repeat(" ", $this->distanceToLeftOuterBorder);
        $paddingRightString = str_repeat(" ", $this->distanceToRightOuterBorder);
	    $borderTopBottomString = $paddingLeftString . $borderTopBottomString . $paddingRightString;

        $borderTopBottomString = $this->addCollisionBordersToRowString($borderTopBottomString);

        return $borderTopBottomString;
    }

	/**
	 * Adds borders to a row string.
	 *
	 * @param String $_rowOutputString The row string
	 * @param int $_y The Y-Coordinate of the row string
	 *
	 * @return String The row string with added borders
	 */
    public function addBordersToRowString(String $_rowOutputString, int $_y): String
    {
        $rowString = $_rowOutputString;

        if ($this->hasTopBorder() && $_y == $this->topLeftCornerCoordinate->y())
        { // Inner top border
            $rowString = $this->getBorderTopString() . "\n" . $rowString;
        }
        if ($this->hasBottomBorder() && $_y == $this->bottomRightCornerCoordinate->y() + (int)$this->hasTopBorder() + 1)
        { // Inner border bottom
        	$rowString .= "\n" . $this->getBorderBottomString();
        }

        // TODO: Left right border

        return $rowString;
    }



    public function addCollisionBorderToTopOuterBorder(String $_topOuterBorderString): String
    {
        if ($this->hasTopBorder()) $borderSymbol = "X"; // TODO: Get outer border string symbol (somehow........)
        else $borderSymbol = $this->borderSymbolCollisionTopOuterBorder;

        return $this->addCollisionBorderToOuterBorder($_topOuterBorderString, $borderSymbol, $this->borderSymbolPositionsLeftRight);
    }

    public function addCollisionBorderToBottomOuterBorder(String $_bottomOuterBorderString): String
    {
        if (! $this->hasBottomBorder()) $borderSymbol = $this->borderSymbolCollisionBottomOuterBorder;
        else $borderSymbol = "X"; // TODO: Get outer border string symbol (somehow...........)

        return $this->addCollisionBorderToOuterBorder($_bottomOuterBorderString, $borderSymbol, $this->borderSymbolPositionsLeftRight);
    }

    private function addCollisionBorderToOuterBorder(String $_outerBorderString, String $_borderSymbol, array $_borderSymbolPositions): String
    {
        $outerBorderString = $_outerBorderString;
        //if ($_borderSymbol) substr_replace

        foreach ($_borderSymbolPositions as $borderSymbolPosition)
        {
            $outerBorderString = substr_replace($outerBorderString, $_borderSymbol, $borderSymbolPosition, 0);
        }

        return $outerBorderString;
    }

	private function addCollisionBordersToRowString(String $_rowString): String
	{
		$rowString = $_rowString;

		// Left border
		if ($this->hasLeftBorder()) $leftBorderSymbol = "X";
		else $leftBorderSymbol = $this->borderSymbolCollisionLeftOuterBorder;
		$rowString = substr_replace($rowString, $leftBorderSymbol, 0, 1);

		// Right border
		if ($this->hasRightBorder()) $rightBorderSymbol = "X";
		else $rightBorderSymbol = "X";
		$rowString = substr_replace($rowString, $rightBorderSymbol, -1, 1);

		return $rowString;
	}
}
