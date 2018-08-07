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
use Output\BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use Output\BoardRenderer\Text\Border\BorderPart\TextRenderedBorderPart;
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
	    $numberOfBorderSymbols = count($borderSymbols);

	    // Create the rendered border part
	    $renderedBorderPart = new TextRenderedBorderPart();
	    $renderedBorderPart->addBorderSymbol($borderSymbols[0], new Coordinate(0, 0), false, false);
	    for ($x = 1; $x < $numberOfBorderSymbols - 1; $x++)
	    {
		    $renderedBorderPart->addBorderSymbol($borderSymbols[$x], new Coordinate($x, 0), false, true);
	    }
	    $renderedBorderPart->addBorderSymbol($borderSymbols[$numberOfBorderSymbols - 1], new Coordinate($numberOfBorderSymbols - 2, 0), false, false);

	    $_canvas->addRenderedBorderAt($renderedBorderPart, $this->parentBorderPart->startsAt());
    }
}
