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
 * The vertical 1 x <board height> border part of the highlight field border.
 */
class HighLightFieldVerticalBorder extends BaseBorder
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

    private function getXCoordinateHighLightString(Board $_board)
    {
        // TODO: This string must be added above the outer border of the board
        $hasInnerLeftBorder = $this->hasLeftBorder();
        $hasInnerRightBorder = $this->hasRightBorder();

        $paddingLeftLength = $this->highLightFieldCoordinate->x() + (int)$hasInnerLeftBorder;
        $paddingTotalLength = $_board->width() + (int)$hasInnerLeftBorder + (int)$hasInnerRightBorder;

        // Output the X-Coordinate of the highlighted cell above the board
        $paddingLeftString = str_repeat(" ", $paddingLeftLength);
        $xCoordinateHighLightString = str_pad(
            $paddingLeftString . $this->highLightFieldCoordinate->x(),
            $paddingTotalLength
        );

        return $xCoordinateHighLightString . "\n";
    }
}
