<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;
use GameOfLife\Field;

/**
 * Clears the board.
 */
class ClearBoardOption extends BoardEditorOption
{
    /**
     * ClearBoardOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "clear",
            array("clearBoard", "emptyBoard", "reset", "r"),
            "clearBoard",
            "Clears the board"
        );
    }

    /**
     * Clears the board.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function clearBoard(): bool
    {
        $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();

        if ($selectionCoordinates != array()) $this->clearSelection($selectionCoordinates);
        else $this->parentBoardEditor->board()->resetFields();

        $this->parentBoardEditor->outputBoard();
        return false;
    }

    /**
     * Clears the fields inside the selection.
     *
     * @param array $_selectionCoordinates The selection coordinates
     */
    private function clearSelection(array $_selectionCoordinates)
    {
        for ($y = $_selectionCoordinates["A"]["y"]; $y <= $_selectionCoordinates["B"]["y"]; $y++)
        {
            for ($x = $_selectionCoordinates["A"]["x"]; $x <= $_selectionCoordinates["B"]["x"]; $x++)
            {
                /** @var Field $field */
                $field = $this->parentBoardEditor->board()->fields()[$y][$x];
                $field->setValue(false);
            }
        }
    }
}
