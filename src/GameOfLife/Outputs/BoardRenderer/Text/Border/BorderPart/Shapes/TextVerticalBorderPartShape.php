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

	    // Create the rendered border part
	    $renderedBorderPart = new TextRenderedBorderPart();
	    foreach ($borderSymbols as $y => $borderSymbol)
	    {
		    $renderedBorderPart->addBorderSymbol($borderSymbol, new Coordinate(0, $y), true);
	    }

        $_canvas->addRenderedBorderAt($renderedBorderPart, $this->parentBorderPart->startsAt());
    }
}
