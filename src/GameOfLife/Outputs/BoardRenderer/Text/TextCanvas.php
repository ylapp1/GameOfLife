<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;


use Output\BoardRenderer\Base\BaseCanvas;

class TextCanvas extends BaseCanvas
{
    public function doIt()
    {
        $rowOutputSymbols = $this->getRowOutputSymbols($boardFieldRow);
        $this->boardFieldSymbolGrid->addSymbolRow($rowOutputSymbols);
    }

    // TODO: Merge the border and board field symbol grids
}
