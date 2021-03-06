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
 * Copies the selection and deletes it from the board.
 */
class CutSelectionOption extends BoardEditorOption
{
    /**
     * CutSelectionOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "cut";
        $this->aliases = array("cutSelection");
        $this->callback = "cutSelection";
        $this->description = "Copies the selection and deletes it from the board";
        $this->arguments = array();
    }

    /**
     * Copies the selection and deletes it from the board.
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The "copy" exceptions
     */
    public function cutSelection(): Bool
    {
        $this->parentBoardEditor->optionHandler()->parseInput("copy");
        $this->parentBoardEditor->optionHandler()->parseInput("deleteSelection");

        return false;
    }
}
