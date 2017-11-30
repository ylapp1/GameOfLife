<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;

/**
 * Resets the currently edited board to an empty board.
 */
class ResetOption extends BoardEditorOption
{
    /**
     * ResetOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "reset";
        $this->callback = "resetBoard";
        $this->description = "Resets the edited board to an empty board";
        $this->numberOfArguments = 0;
    }

    /**
     * Resets the currently edited board to an empty board and outputs the empty board.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function resetBoard()
    {
        $this->parentBoardEditor->board()->resetBoard();
        $this->parentBoardEditor->output()->outputBoard($this->parentBoardEditor->board());
        return false;
    }
}