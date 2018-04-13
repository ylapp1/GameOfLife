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
 * Displays help text.
 */
class HelpOption extends BoardEditorOption
{
    /**
     * HelpOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "help",
            array("h"),
            "displayHelp",
            "Display help"
        );
    }

    /**
     * Displays a help message which tells the user how to use the board editor.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function displayHelp()
    {
        $helpText = "\nSet the coordinates for the living cells as below:\n"
            . "<X-Coordinate>,<Y-Coordinate>\n"
            . "Enter the coordinates of a set field to unset it.\n"
            . "The game starts when you type \"start\" in a new line and press <Enter>\n"
            . "You can save your board configuration before starting the simulation by typing \"save\"\n"
            . "Type \"options\" to see a list of all valid options\n"
            . "Let's Go:\n\n";

        echo $helpText;

        return false;
    }
}
