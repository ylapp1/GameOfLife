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
    /**
     * DeleteSelectionOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
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
     *
     * @throws \Exception The exception when the selection coordinates are empty
     */
    public function deleteSelection(): Bool
    {
        $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();
        if ($selectionCoordinates == array()) throw new \Exception("There are no fields selected at the moment.");

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
