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
        $this->aliases = array("clearBoard", "emptyBoard");
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
        $this->parentBoardEditor->board()->resetBoard();
        $this->parentBoardEditor->output()->outputBoard($this->parentBoardEditor->board());
        return false;
    }
}
