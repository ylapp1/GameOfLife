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
 * Shows the board.
 */
class ShowBoardOption extends BoardEditorOption
{
    /**
     * ShowBoardOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "show";
        $this->aliases = array("showBoard", "printBoard", "outputBoard");
        $this->callback = "showBoard";
        $this->description = "Shows the board";
        $this->arguments = array();
    }

    /**
     * Shows the board.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function showBoard()
    {
        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
