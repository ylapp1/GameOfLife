<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\BorderPart\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BorderPart\Shapes\HorizontalBorderPartShape;
use Output\BoardRenderer\Text\BorderPart\TextBorderPart;
use Output\BoardRenderer\Text\BorderSymbolGrid;


class TextHorizontalBorderPartShape extends HorizontalBorderPartShape
{
    /**
     * The parent border part
     *
     * @var TextBorderPart $parentBorderPart
     */
    protected $parentBorderPart;


    /**
     * TextHorizontalBorderPartShape constructor.
     * @param TextBorderPart $_parentBorderPart
     */
    public function __construct(TextBorderPart $_parentBorderPart)
    {
        parent::__construct($_parentBorderPart);
    }


    /**
     * Draws the parent border part to a symbol grid.
     *
     * @param BorderSymbolGrid $_symbolGrid The symbol grid
     */
    public function drawBorderPartToSymbolGrid($_symbolGrid)
    {
        $borderSymbols = $this->parentBorderPart->getBorderSymbols();
        $totalLength = $this->getTotalLength();

        $startX = $this->parentBorderPart->startsAt()->x();
        $startY = $this->parentBorderPart->startsAt()->y();
        $borderSymbolEndIndex = $startX + $totalLength;

        $_symbolGrid->setBorderRowSymbolAt(new Coordinate($startX, $startY), $borderSymbols[0]);
        for ($x = 1; $x < $totalLength - 2; $x++)
        {
            $xPositionOnGrid = $startX + $x;
            $_symbolGrid->setBorderRowSymbolAt(new Coordinate($xPositionOnGrid, $startY), $borderSymbols[$x]);
        }
        $_symbolGrid->setBorderRowSymbolAt(new Coordinate($borderSymbolEndIndex, $startY), $borderSymbols[$borderSymbolEndIndex]);
    }
}
