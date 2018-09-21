<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use Simulator\Board;
use Util\FileSystem\FileSystemWriter;

/**
 * Saves a custom template to the custom templates directory.
 */
class TemplateSaver extends TemplateHandler
{
    // Attributes

    /**
     * The file system writer
     *
     * @var FileSystemWriter $fileSystemWriter
     */
    private $fileSystemWriter;


    // Magic Methods

    /**
     * TemplateSaver constructor.
     *
     * @param String $_defaultTemplatesDirectory
     */
    public function __construct(String $_defaultTemplatesDirectory)
    {
        parent::__construct($_defaultTemplatesDirectory);
        $this->fileSystemWriter = new FileSystemWriter();
    }


    // Class Methods

    /**
     * Saves a custom template to the custom templates directory.
     *
     * @param String $_templateName The template name
     * @param Board $_board The board whose fields will be saved to the template file
     * @param Bool $_overwriteIfExists Indicates whether an existing template file with that name should be overwritten
     *
     * @throws \Exception The exception when the file could not be written
     */
    public function saveCustomTemplate(String $_templateName, Board $_board, Bool $_overwriteIfExists = false)
    {
        $this->fileSystemWriter->writeFile($this->customTemplatesDirectory . "/" . $_templateName . ".txt", $_board, false, $_overwriteIfExists);
    }
}
