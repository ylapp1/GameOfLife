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
use Output\BoardRenderer\Text\BorderPart\TextRenderedBorderPart;
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

        // Create the rendered border part
	    $renderedBorderPart = new TextRenderedBorderPart();
	    for ($x = 0; $x < $totalLength - 1; $x++)
	    {
		    $renderedBorderPart->addBorderSymbol($borderSymbols[$x], new Coordinate($x, 0), false);
	    }

	    $_canvas->addRenderedBorderAt($renderedBorderPart, $this->parentBorderPart->startsAt());
    }
}
