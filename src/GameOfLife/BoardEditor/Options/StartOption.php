<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;

/**
 * Starts the simulation with the edited board.
 */
class StartOption extends BoardEditorOption
{
    /**
     * StartOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "start",
            array(),
            "startSimulation",
            "Starts the simulation"
        );
    }

    /**
     * Starts the simulation by ending the board editor session.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function startSimulation()
    {
        return true;
    }
}
