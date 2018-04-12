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
        $this->arguments = array("X-Coordinate (left border)" => "int", "Y-Coordinate (top border)" => "int");
    }

    /**
     * Moves the selection to another area on the board.
     *
     * @param int $_posX The X-Position of the top left corner of the template on the board
     * @param int $_posY The Y-Position of the top left corner of the template on the board
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The "cut" and "paste" exceptions
     */
    public function moveSelection(int $_posX, int $_posY): Bool
    {
        $this->parentBoardEditor->optionHandler()->parseInput("cut");
        $this->parentBoardEditor->optionHandler()->parseInput("paste " . $_posX . " " . $_posY);

        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
