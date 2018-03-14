<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use Utils\FileSystemHandler;

/**
 * Parent class for TemplateListPrinter, TemplateLoader and TemplateSaver.
 */
class TemplateHandler
{
    /**
     * FileSystemHandler that reads templates
     *
     * @var FileSystemHandler $fileSystemHandler
     */
    protected $fileSystemHandler;

    /**
     * The directory in which the default templates are stored.
     *
     * @var String $defaultTemplatesDirectory
     */
    protected $defaultTemplatesDirectory;

    /**
     * The directory in which the custom templates are stored.
     *
     * @var String $customTemplatesDirectory
     */
    protected $customTemplatesDirectory;


    /**
     * TemplateLoader constructor.
     *
     * @param String $_templatesBaseDirectory The base directory for default and custom templates
     */
    public function __construct(String $_templatesBaseDirectory)
    {
        $this->fileSystemHandler = new FileSystemHandler();
        $this->defaultTemplatesDirectory = $_templatesBaseDirectory;
        $this->customTemplatesDirectory = $_templatesBaseDirectory . "/Custom";
    }
}
