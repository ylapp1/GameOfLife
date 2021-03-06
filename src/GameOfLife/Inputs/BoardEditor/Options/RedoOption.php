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
 * Restores the next board in the BoardEditors history.
 */
class RedoOption extends BoardEditorOption
{
    /**
     * RedoOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "redo";
        $this->callback = "restoreNextBoard";
        $this->description = "Restores the next board in the history";
        $this->arguments = array();
    }

    /**
     * Restores the next board in the BoardEditors history.
     *
     * @throws \Exception
     */
    public function restoreNextBoard()
    {
        $this->parentBoardEditor->restoreNextBoard();
        $this->parentBoardEditor->outputBoard("Next board restored.");

        return false;
    }
}
