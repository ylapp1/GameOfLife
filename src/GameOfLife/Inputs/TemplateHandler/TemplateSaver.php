<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Board;
use Utils\FileSystemHandler;

/**
 * Saves templates in a template directory.
 */
class TemplateSaver extends TemplateHandler
{
    /**
     * TemplateSaver constructor.
     *
     * @param String $_templateDirectory Template directory
     */
    public function __construct(String $_templateDirectory)
    {
        parent::__construct($_templateDirectory);
    }

    /**
     * Saves a template to a file in the template directory
     *
     * @param String $_templateName Template name
     * @param Board $_board Board whose fields will be saved to the template
     * @param bool $_overwriteIfExists Indicates whether an existing template file with that name should be overwritten
     *
     * @return bool true: Template successfully saved
     *              false: Error while saving template
     */
    public function saveTemplate(String $_templateName, Board $_board, bool $_overwriteIfExists = false): bool
    {
        $this->fileSystemHandler->createDirectory($this->templateDirectory . "/Custom");
        $fileName = $_templateName . ".txt";

        $error = $this->fileSystemHandler->writeFile($this->templateDirectory . "/Custom", $fileName, $_board, $_overwriteIfExists);

        if ($error === FileSystemHandler::NO_ERROR) return true;
        else return false;
    }
}
