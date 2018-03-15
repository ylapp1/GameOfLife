<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Board;

/**
 * Saves templates in a template directory.
 */
class TemplateSaver extends TemplateHandler
{
    /**
     * TemplateSaver constructor.
     *
     * @param String $_templatesBaseDirectory The base directory for default and custom templates
     */
    public function __construct(String $_templatesBaseDirectory)
    {
        parent::__construct($_templatesBaseDirectory);
    }

    /**
     * Saves a custom template to the custom templates directory.
     *
     * @param String $_templateName The template name
     * @param Board $_board The board whose fields will be saved to the template
     * @param Bool $_overwriteIfExists Indicates whether an existing template file with that name should be overwritten
     *
     * @throws \Exception
     */
    public function saveCustomTemplate(String $_templateName, Board $_board, Bool $_overwriteIfExists = false)
    {
        try
        {
            $this->fileSystemHandler->writeFile($this->customTemplatesDirectory, $_templateName . ".txt", $_board, $_overwriteIfExists);
        }
        catch (\Exception $_exception)
        {
            throw new \Exception("Error while saving template: " . $_exception->getMessage());
        }
    }
}
