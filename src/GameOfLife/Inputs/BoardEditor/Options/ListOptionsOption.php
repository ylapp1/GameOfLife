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
        $this->arguments = array();
    }

    /**
     * Lists all available options.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function listOptions()
    {
        $optionUsageOutputs = array();
        $longestOptionLength = 0;

        // Get the length of the longest option name
        foreach ($this->parentBoardEditor->optionHandler()->options() as $optionName => $option)
        {
            $optionUsageOutput = $optionName;
            foreach ($option->arguments() as $argumentName)
            {
                $optionUsageOutput .= " <" . $argumentName . ">";
            }

            $optionUsageOutputs[$optionUsageOutput] = $option->description();

            if (strlen($optionUsageOutput) > $longestOptionLength) $longestOptionLength = strlen($optionUsageOutput);
        }

        // Output the option list
        $output = "\n\nOptions:";
        foreach ($optionUsageOutputs as $optionUsageOutput => $optionDescription)
        {
            $output .= "\n - " . str_pad($optionUsageOutput, $longestOptionLength + 1) . ": " . $optionDescription;
        }
        $output .= "\n\n";

        echo $output;

        return false;
    }
}
