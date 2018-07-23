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
use Output\BoardRenderer\Text\TextCanvas;

/**
 * Class for horizontal text border parts.
 */
class TextHorizontalBorderPartShape extends HorizontalBorderPartShape
{
    // Class Methods

    /**
     * Draws the parent border part to a canvas.
     *
     * @param TextCanvas $_canvas The canvas
     */
    public function addBorderPartToCanvas($_canvas)
    {
        /** @var TextBorderPart $parentBorderPart */
        $parentBorderPart = $this->parentBorderPart;

        $borderSymbols = $parentBorderPart->getBorderSymbols();
        $totalLength = $this->getTotalLength();

        $startX = $this->parentBorderPart->startsAt()->x();
        $startY = $this->parentBorderPart->startsAt()->y();

        $_canvas->addRenderedBorderAt(new Coordinate($startX, $startY), $borderSymbols[0]);
        for ($x = 1; $x < $totalLength - 2; $x++)
        {
            $xPositionOnGrid = $startX + $x;
            $_canvas->addRenderedBorderAt(new Coordinate($xPositionOnGrid, $startY), $borderSymbols[$x]);
        }
        $_canvas->addRenderedBorderAt(new Coordinate($startX + $totalLength, $startY), $borderSymbols[$totalLength]);

        // TODO: Need method to add single border symbol to canvas
    }
}
