<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\BorderPart\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BorderPart\Shapes\VerticalBorderPartShape;
use Output\BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use Output\BoardRenderer\Text\Border\BorderPart\TextRenderedBorderPart;
use Output\BoardRenderer\Text\TextCanvas;

/**
 * Class for vertical text border parts.
 */
class TextVerticalBorderPartShape extends VerticalBorderPartShape
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
        $numberOfBorderSymbols = count($borderSymbols);

	    // Create the rendered border part
	    $renderedBorderPart = new TextRenderedBorderPart();
	    $renderedBorderPart->addBorderSymbol($borderSymbols[0], new Coordinate(0, 0), false, false);
	    for ($y = 0; $y < $numberOfBorderSymbols - 2; $y++)
	    {
		    $renderedBorderPart->addBorderSymbol($borderSymbols[$y + 1], new Coordinate(0, $y), true, false);
	    }
	    $renderedBorderPart->addBorderSymbol($borderSymbols[$numberOfBorderSymbols - 1], new Coordinate(0, $numberOfBorderSymbols - 2), false, false);

        $_canvas->addRenderedBorderAt($renderedBorderPart, $this->parentBorderPart->startsAt());
    }
}
