<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border;

use GameOfLife\Board;
use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Text\Border\Shapes\TextRectangleBorderShape;

/**
 * Generates border strings for boards.
 */
class BoardOuterBorder extends BaseBorder
{
    // Magic Methods

    /**
     * BoardOuterBorder constructor.
     *
     * @param Board $_board The board for which the outer border will be created
     */
    public function __construct(Board $_board)
    {
        $topLeftCornerCoordinate = new Coordinate(0, 0);
        $bottomRightCornerCoordinate = new Coordinate($_board->width() - 1, $_board->height() - 1);

        parent::__construct(
            null,
            new TextRectangleBorderShape(
                $this,
                $topLeftCornerCoordinate,
                $bottomRightCornerCoordinate,
                "╔",
                "╗",
                "╚",
                "╝",
                "═",
                "║"
            )
        );
    }
}
