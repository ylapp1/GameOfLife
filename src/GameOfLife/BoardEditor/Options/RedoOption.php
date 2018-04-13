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
        parent::__construct(
            $_parentBoardEditor,
            "redo",
            array(),
            "restoreNextBoard",
            "Restores the next board in the history"
        );
    }

    /**
     * Restores the next board in the BoardEditors history.
     *
     * @throws \Exception
     */
    public function restoreNextBoard()
    {
        $nextBoard = $this->parentBoardEditor->boardHistorySaver()->getNextBoard();
        if ($nextBoard == null) throw new \Exception("There is no next board in the history.");

        $this->parentBoardEditor->board()->copy($nextBoard);
        $this->parentBoardEditor->outputBoard("Next board restored.");

        return false;
    }
}
