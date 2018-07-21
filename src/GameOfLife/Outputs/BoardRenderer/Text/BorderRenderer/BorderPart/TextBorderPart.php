<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\BorderPart;

use Output\BoardRenderer\Base\BaseSymbolGrid;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

class TextBorderPart extends BaseBorderPart
{
    /**
     * Renders this border part and adds it to a symbol grid.
     *
     * @param BaseSymbolGrid $_symbolGrid The symbol grid
     */
    public function addToSymbolGrid($_symbolGrid)
    {
        $this->renderCollisions();
    }

    private function renderCollisions()
    {
        foreach ($this->collisions as $collision)
        {
            if ($collision->isOuterBorderCollision())
            {
                if ($collision->position() == 0)
                {
                    // Outer border collision start symbol
                }
                elseif ($collision->position() == $this->getTotalLength())
                {
                    // Outer border collision end symbol
                }
                else
                {
                    // Outer border collision center symbol
                }
            }
            else
            {
                if ($collision->position() == 0)
                {
                    // Inner border collision start symbol
                }
                elseif ($collision->position() == $this->getTotalLength())
                {
                    // Inner border collision end symbol
                }
                else
                {
                    // Inner border collision center symbol
                }
            }
        }
    }
}
