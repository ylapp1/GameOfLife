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
 * Exits the board editor without starting the simulation.
 */
class ExitOption extends BoardEditorOption
{
    /**
     * ExitOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "exit";
        $this->callback = "exitBoardEditor";
        $this->description = "Exit the application";
    }

    /**
     * Resets the board and ends the board editing session.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function exitBoardEditor()
    {
        $this->parentBoardEditor->board()->resetBoard();
        return true;
    }
}