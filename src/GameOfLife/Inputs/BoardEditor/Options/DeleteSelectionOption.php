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

/**
 * Unsets the fields in the current selection area.
 */
class DeleteSelectionOption extends BoardEditorOption
{
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "deleteSelection";
        $this->callback = "deleteSelection";
        $this->description = "Unsets the fields in the current selection area";
        $this->arguments = array();
    }

    /**
     * Unsets the fields in the current selection area.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function deleteSelection(): Bool
    {
        $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();

        for ($y = $selectionCoordinates["A"]["y"]; $y <= $selectionCoordinates["B"]["y"]; $y++)
        {
            for ($x = $selectionCoordinates["A"]["x"]; $x <= $selectionCoordinates["B"]["x"]; $x++)
            {
                $this->parentBoardEditor->board()->setField($x, $y, false);
            }
        }

        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
