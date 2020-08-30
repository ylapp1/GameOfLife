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
 * Restores the previous board in the BoardEditors history.
 */
class UndoOption extends BoardEditorOption
{
    /**
     * UndoOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "undo";
        $this->callback = "restoreLastBoard";
        $this->description = "Restores the previous board in the history";
        $this->arguments = array();
    }

    /**
     * Restores the previous board in the BoardEditors history.
     *
     * @throws \Exception
     */
    public function restoreLastBoard()
    {
        $this->parentBoardEditor->restorePreviousBoard();
        $this->parentBoardEditor->outputBoard("Previous board restored.");

        return false;
    }
}
