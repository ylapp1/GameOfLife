<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border;

use BoardEditor\SelectionArea;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\Border\Shapes\TextRectangleBorderShape;

/**
 * Prints the borders for selection areas inside boards.
 */
class SelectionAreaBorder extends BaseBorder
{
    // Magic Methods

    /**
     * SelectionAreaBorder constructor.
     */
    public function __construct($_parentBorder, SelectionArea $_selectionArea)
    {
        parent::__construct(
            $_parentBorder,
            new TextRectangleBorderShape(
                $this,
                $_selectionArea->topLeftCornerCoordinate(),
                $_selectionArea->bottomRightCornerCoordinate(),
                "┏",
                "┓",
                "┗",
                "┛",
                "╍",
                "┋",
                "╤",
                "╧",
                "╟",
                "╢"
            )
        );
    }
}
