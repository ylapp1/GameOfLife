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
 * Moves the selection to another area on the board.
 */
class MoveSelectionOption extends BoardEditorOption
{
    /**
     * MoveSelectionOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "move";
        $this->aliases = array("moveSelection");
        $this->callback = "moveSelection";
        $this->description = "Moves the selection to another area on the board";
        $this->arguments = array();
    }

    /**
     * Moves the selection to another area on the board.
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The "cut" and "paste" exceptions
     */
    public function moveSelection(): Bool
    {
        $this->parentBoardEditor->optionHandler()->parseInput("cut");
        $this->parentBoardEditor->optionHandler()->parseInput("paste");

        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
