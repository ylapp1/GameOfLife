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
 * Lists all available board editor options.
 */
class ListOptionsOption extends BoardEditorOption
{
    /**
     * ListOptionsOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "options";
        $this->callback = "listOptions";
        $this->description = "Lists available options";
    }

    /**
     * Lists all available options.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function listOptions()
    {
        // Get the length of the longest option name
        $longestOptionLength = 0;
        foreach ($this->parentBoardEditor->options() as $optionName => $option)
        {
            if (strlen($optionName) > $longestOptionLength) $longestOptionLength = strlen($optionName);
        }

        // Output the option list
        $output = "\n\nOptions:";
        foreach ($this->parentBoardEditor->options() as $optionName => $option)
        {
            $output .= "\n - " . str_pad($optionName, $longestOptionLength + 1) . ": " . $option->description();
        }
        $output .= "\n\n";

        echo $output;

        return false;
    }
}