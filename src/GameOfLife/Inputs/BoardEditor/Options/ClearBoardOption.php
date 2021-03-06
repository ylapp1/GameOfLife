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
        parent::__construct($_parentBoardEditor);

        $this->name = "clear";
        $this->aliases = array("clearBoard", "emptyBoard", "reset", "r");
        $this->callback = "clearBoard";
        $this->description = "Clears the board";
        $this->arguments = array();
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
        else $this->parentBoardEditor->board()->resetBoard();

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
