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
 * Inverts the board.
 */
class InvertBoardOption extends BoardEditorOption
{
    /**
     * InvertBoardOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "invert";
        $this->aliases = array("invertBoard");
        $this->callback = "invertBoard";
        $this->description = "Inverts the board";
        $this->arguments = array();
    }

    /**
     * Inverts the board.
     */
    public function invertBoard()
    {
        $this->parentBoardEditor->board()->invertBoard();
        $this->parentBoardEditor->output()->outputBoard($this->parentBoardEditor->board());
        return false;
    }
}
