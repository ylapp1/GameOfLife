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
use TemplateHandler\TemplateSaver;

/**
 * Saves a template to a file.
 * Must get a template name argument
 */
class SaveTemplateOption extends BoardEditorOption
{
    /**
     * The template saver that is used to save templates
     *
     * @var TemplateSaver
     */
    private $templateSaver;


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
        $this->arguments = array("template name");

        $this->templateSaver = new TemplateSaver($this->parentBoardEditor->templateDirectory());
    }


    /**
     * Returns the template saver.
     *
     * @return TemplateSaver The template saver
     */
    public function templateSaver(): TemplateSaver
    {
        return $this->templateSaver;
    }

    /**
     * Sets the template saver.
     *
     * @param TemplateSaver $_templateSaver New template saver
     */
    public function setTemplateSaver(TemplateSaver $_templateSaver)
    {
        $this->templateSaver = $_templateSaver;
    }


    /**
     * Saves current board to a custom template file.
     *
     * @param string $_templateName Thee template name
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the template saver fails to save the template
     */
    public function saveTemplate($_templateName)
    {
        $result = $this->templateSaver->saveCustomTemplate($_templateName, $this->parentBoardEditor->board());

        if ($result == false)
        {
            echo "Warning: A template with that name already exists.\n";
            $input = $this->parentBoardEditor->readInput("Overwrite the old file? (Yes|No): ");

            if (stristr($input, "y") || stristr($input, "yes"))
            {
                $this->templateSaver->saveCustomTemplate($_templateName, $this->parentBoardEditor->board(), true);
                echo "Template successfully replaced!\n\n";
            }
            else echo "Saving aborted.\n\n";
        }
        else echo "Template successfully saved!\n\n";

        echo "You can set/unset more cells or start the simulation by typing \"start\"\n\n";

        return false;
    }
}
