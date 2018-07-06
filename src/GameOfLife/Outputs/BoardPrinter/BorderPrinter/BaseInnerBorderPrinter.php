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
 */
abstract class BaseInnerBorderPrinter extends BaseBorderPrinter
{
    private $borderSymbolTopOuterBorder;
    private $borderSymbolBottomOuterBorder;
    private $borderSymbolLeftOuterBorder;
    private $borderSymbolRightOuterBorder;

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

    protected $borderSymbolPositionsTopBottom;
    protected $borderSymbolPositionsLeftRight;


    protected function __construct($_borderSymbolTopLeft, $_borderSymbolTopRight, $_borderSymbolBottomLeft, $_borderSymbolBottomRight, $_borderSymbolTopBottom, $_borderSymbolLeftRight, $_borderSymbolTopOuterBorder, $_borderSymbolBottomOuterBorder, $_borderSymbolLeftOuterBorder, $_borderSymbolRightOuterBorder)
    {
        parent::__construct($_borderSymbolTopLeft, $_borderSymbolTopRight, $_borderSymbolBottomLeft, $_borderSymbolBottomRight, $_borderSymbolTopBottom, $_borderSymbolLeftRight);

        $this->borderSymbolTopOuterBorder = $_borderSymbolTopOuterBorder;
        $this->borderSymbolBottomOuterBorder = $_borderSymbolBottomOuterBorder;
        $this->borderSymbolLeftOuterBorder = $_borderSymbolLeftOuterBorder;
        $this->borderSymbolRightOuterBorder = $_borderSymbolRightOuterBorder;
    }

    protected function init(Board $_board, Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate)
    {
        $this->distanceToTopOuterBorder = $_topLeftCornerCoordinate->y();
        $this->distanceToBottomOuterBorder = ($_board->height() - 1) - $_bottomRightCornerCoordinate->y();
        $this->distanceToLeftOuterBorder = $_topLeftCornerCoordinate->x();
        $this->distanceToRightOuterBorder = ($_board->width() - 1) - $_bottomRightCornerCoordinate->x();

        $this->topLeftCornerCoordinate = $_topLeftCornerCoordinate;
        $this->bottomRightCornerCoordinate = $_bottomRightCornerCoordinate;

        /*
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
        */
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


    public function getBorderTopString(Board $_board): String
    {
        return $this->getBorderTopBottomString(
            $_board, $this->borderSymbolTopLeft, $this->borderSymbolTopRight
        );
    }

    public function getBorderBottomString(Board $_board): String
    {
        return $this->getBorderTopBottomString(
            $_board, $this->borderSymbolBottomLeft, $this->borderSymbolBottomRight
        );
    }

    private function getBorderTopBottomString(Board $_board, String $_borderLeftSymbol, String $_borderRightSymbol)
    {
        if ($this->hasLeftBorder()) $borderLeftSymbol = $_borderLeftSymbol;
        else
        {

            $borderLeftSymbol = $this->borderSymbolTopBottom;
        }

        if ($this->hasRightBorder()) $borderRightSymbol = $_borderRightSymbol;
        else $borderRightSymbol = $this->borderSymbolTopBottom;

        $lineWidth = $_board->width() + (int)$this->hasLeftBorder() + (int)$this->hasRightBorder();

        $borderTopString = $this->getHorizontalLineString(
            $lineWidth, $borderLeftSymbol, $borderRightSymbol, $this->borderSymbolTopBottom
        );

        $borderTopString = $this->addCollisionBorderToRightOuterBorder($borderTopString);
        $borderTopString = $this->addCollisionBorderToRightOuterBorder($borderTopString);

        return $borderTopString;
    }


    public function addBordersToRowString(String $_rowString, int $_y): String
    {
        $rowString = $this->addCollisionBorderToLeftOuterBorder($_rowString);
        $rowString = $this->addCollisionBorderToRightOuterBorder($rowString);

        if ($this->hasTopBorder() && $_y == $this->topLeftCornerCoordinate->y())
        { // Inner top border
            //$rowString = $this->getBorderTopString

        }
        if ($this->hasBottomBorder() && $_y == $this->bottomRightCornerCoordinate->y() + (int)$this->hasTopBorder() + 1)
        { // Inner border bottom

        }

        return $rowString;
    }

    public function addCollisionBorderToTopOuterBorder(String $_topOuterBorderString): String
    {
        if (! $this->hasTopBorder())
        {
            $borderSymbol = $this->borderSymbolTopOuterBorder;
            return $this->addCollisionBorderToOuterBorder($_topOuterBorderString, $borderSymbol, $this->borderSymbolPositionsLeftRight);
        }
        else $borderSymbol = "X"; // TODO: Get outer border string symbol (somehow........)
    }

    public function addCollisionBorderToBottomOuterBorder(String $_bottomOuterBorderString): String
    {
        if (! $this->hasBottomBorder()) $borderSymbol = $this->borderSymbolBottomOuterBorder;
        else $borderSymbol = "X"; // TODO: Get outer border string symbol (somehow...........)

        return $this->addCollisionBorderToOuterBorder($_bottomOuterBorderString, $borderSymbol, $this->borderSymbolPositionsLeftRight);
    }

    public function addCollisionBorderToLeftOuterBorder(String $_leftOuterBorderString): String
    {
        if (! $this->hasLeftBorder()) $borderSymbol = $this->borderSymbolLeftOuterBorder;
        else $borderSymbol = "X"; // TODO: Get outer border string symbol (somehow...........)

        return $this->addCollisionBorderToOuterBorder($_leftOuterBorderString, $borderSymbol, $this->borderSymbolPositionsTopBottom);
    }

    private function addCollisionBorderToRightOuterBorder(String $_rightOuterBorderString): String
    {
        if (! $this->hasRightBorder()) $borderSymbol = $this->borderSymbolRightOuterBorder;
        else $borderSymbol = "X"; // TODO: Get outer border string symbol (somehow.............)

        return $this->addCollisionBorderToOuterBorder($_rightOuterBorderString, $borderSymbol, $this->borderSymbolPositionsTopBottom);
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
}
