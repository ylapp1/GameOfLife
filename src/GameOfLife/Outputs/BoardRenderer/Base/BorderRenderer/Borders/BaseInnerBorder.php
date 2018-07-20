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
use Output\BoardPrinter\OutputBoard\BorderPartBuilder\BaseBorder;

/**
 * Parent class for inner border printers.
 * Inner borders must be located inside an outer border and they can overlap with other inner borders or touch the outer
 * border.
 */
abstract class BaseInnerBorder extends BaseBorder
{
	// Attributes

	/**
	 * The symbol that will be placed in the top outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionTopOuterBorder
	 */
    protected $borderSymbolCollisionTopOuterBorder;

	/**
	 * The symbol that will be placed in the bottom outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionBottomOuterBorder
	 */
	protected $borderSymbolCollisionBottomOuterBorder;

	/**
	 * The symbol that will be placed in the left outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionLeftOuterBorder
	 */
	protected $borderSymbolCollisionLeftOuterBorder;

	/**
	 * The symbol that will be placed in the right outer border when this border collides with it
	 *
	 * @var String $borderSymbolCollisionRightOuterBorder
	 */
	protected $borderSymbolCollisionRightOuterBorder;


    protected $distanceToTopOuterBorder;
    protected $distanceToBottomOuterBorder;
    protected $distanceToLeftOuterBorder;
    protected $distanceToRightOuterBorder;

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
    protected function __construct(String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight, String $_borderSymbolCollisionTopOuterBorder, String $_borderSymbolCollisionBottomOuterBorder, String $_borderSymbolCollisionLeftOuterBorder, String $_borderSymbolCollisionRightOuterBorder)
    {
        parent::__construct($_borderSymbolTopLeft, $_borderSymbolTopRight, $_borderSymbolBottomLeft, $_borderSymbolBottomRight, $_borderSymbolTopBottom, $_borderSymbolLeftRight);

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
    }


    public function hasTopBorder()
    {
    	return $this->distanceToTopOuterBorder;
    }

    public function hasBottomBorder()
    {
    	return $this->distanceToBottomOuterBorder;
    }

    public function hasLeftBorder()
    {
    	return $this->distanceToLeftOuterBorder;
    }

    public function hasRightBorder()
    {
    	return $this->distanceToRightOuterBorder;
    }
}
