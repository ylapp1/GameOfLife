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
 * Saves a template to a file.
 * Must get a template name argument
 */
class SaveTemplateOption extends BoardEditorOption
{
    /**
     * SaveTemplateOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "save";
        $this->callback = "saveTemplate";
        $this->description = "Saves the board as a template";
    }

    /**
     * Saves current board to a custom template file.
     *
     * @param string $_templateName Thee template name
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function saveTemplate(String $_templateName)
    {
        if ($_templateName == null) echo "Error: Invalid template name!\n";
        else
        {
            $result = $this->parentBoardEditor->templateSaver()->saveTemplate($_templateName, $this->parentBoardEditor->board());

            if ($result == false)
            {
                echo "Warning: A template with that name already exists. Overwrite the old file? (Y|N)\n";
                $input = $this->parentBoardEditor->readInput("php://stdin");

                if (stristr($input, "y") || stristr($input, "yes"))
                {
                    $this->parentBoardEditor->templateSaver()->saveTemplate($_templateName, $this->parentBoardEditor->board(), true);
                    echo "Template successfully replaced!\n\n";
                }
                else echo "Saving aborted.\n\n";
            }
            else echo "Template successfully saved!\n\n";

            echo 'You can set/unset more cells or start the simulation by typing "start"' . "\n\n";
        }

        return false;
    }
}