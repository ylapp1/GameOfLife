<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border;

use Output\BoardRenderer\Base\Border\BaseBorder;

/**
 * The horizontal <board width> x 1 border part of the high light field border.
 */
class HighLightFieldHorizontalBorder extends BaseBorder
{
    public function __construct($_parentBorder = null, $_shape)
    {
        $this->shape = array(
            "┼",
            "┼",
            "┼",
            "┼",
            "─",
            "│",
            "╤",
            "╧",
            "╟",
            "╢"
        );

        parent::__construct($_parentBorder, $_shape);
    }

    public function addBordersToRowString(String $_rowOutputString, int $_y): String
    {
        // TODO: This string must be added right to the outer border
        $rowOutputString = $_rowOutputString;

        if ($this->highLightFieldCoordinate && $_y == $this->highLightFieldCoordinate->y())
        {
            $rowOutputString .= " " . $_y;
        }

        return parent::addBordersToRowString($rowOutputString, $_y);
    }
}
