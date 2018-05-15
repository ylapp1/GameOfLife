<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;

/**
 * Toggles a field on the board.
 */
class ToggleFieldOption extends BoardEditorOption
{
    /**
     * ToggleFieldOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "toggle",
            array(),
            "toggleField",
            "Toggles a field",
            array(
                "X-Coordinate" => "int",
                "Y-Coordinate" => "int")
        );
    }

    /**
     * Sets a field on the board and displays the updated board or displays an error in case of invalid coordinates.
     *
     * @param int $_x X-Coordinate of the field
     * @param int $_y Y-Coordinate of the field
     *
     * @return Bool Indicates whether the board editor session is finished
     *
     * @throws \Exception The exception when one of the coordinates exceeds the board borders
     */
    public function toggleField(int $_x, int $_y): Bool
    {
        $this->parentBoardEditor->checkCoordinate($_x, "X",0, $this->parentBoardEditor->board()->width() - 1);
        $this->parentBoardEditor->checkCoordinate($_y, "Y", 0, $this->parentBoardEditor->board()->height() - 1);

        $currentCellState = $this->parentBoardEditor->board()->getFieldState($_x, $_y);
        $this->parentBoardEditor->board()->setFieldState($_x, $_y, !$currentCellState);
        $this->parentBoardEditor->setHighLightField($_x, $_y);
        $this->parentBoardEditor->outputBoard();

        return false;
    }
}
