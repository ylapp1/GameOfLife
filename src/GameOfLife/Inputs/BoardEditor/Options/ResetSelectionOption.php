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
 * Resets the current selection.
 */
class ResetSelectionOption extends BoardEditorOption
{
    /**
     * ResetSelectionOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "resetSelection";
        $this->aliases = array("unsetSelection", "unselect");
        $this->callback = "resetSelection";
        $this->description = "Resets the current selection";
        $this->arguments = array();
    }

    /**
     * Resets the current selection and outputs the board.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function resetSelection(): Bool
    {
        $this->parentBoardEditor->setSelectionCoordinates(array());
        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
