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
use Utils\Shell\ShellTablePrinter;

/**
 * Lists all available board editor options.
 */
class ListOptionsOption extends BoardEditorOption
{
    /**
     * The shell output helper
     *
     * @var ShellTablePrinter $shellTablePrinter
     */
    private $shellTablePrinter;

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

        $this->shellTablePrinter = new ShellTablePrinter();
    }

    /**
     * Lists all available options.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function listOptions()
    {
        $outputTable = array();

        // Get the length of the longest option name
        foreach ($this->parentBoardEditor->optionHandler()->options() as $optionName => $option)
        {
            $optionUsageOutput = " - " . $optionName;
            foreach ($option->arguments() as $argumentName => $argumentType)
            {
                $optionUsageOutput .= " <" . $argumentName . ">";
            }

            $outputTable[] = array($optionUsageOutput, ": " . $option->description());
        }

        // Output the option list
        echo "\n\nOptions:\n";
        $this->shellTablePrinter->printTable($outputTable, 3, array(), true);
        echo "\n\n";

        return false;
    }
}
