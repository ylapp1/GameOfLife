<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use BoardRenderer\Base\BaseBoardFieldRenderer;

/**
 * Renders a list of board fields as symbols and adds them to a canvas.
 *
 * Call renderBoardFields() to render a list of board fields and add them to a canvas
 */
class TextBoardFieldRenderer extends BaseBoardFieldRenderer
{
    // Magic Methods

    /**
     * TextBoardFieldRenderer constructor.
     *
     * @param String $_cellAliveSymbol The symbol that is used to print a living cell
     * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
     */
    public function __construct(String $_cellAliveSymbol = null, String $_cellDeadSymbol = null)
    {
    	parent::__construct($_cellAliveSymbol, $_cellDeadSymbol);
    }
}
