<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use Utils\FileSystem\FileSystemReader;
use Utils\FileSystem\FileSystemWriter;

/**
 * Parent class for TemplateListPrinter, TemplateLoader and TemplateSaver.
 */
class TemplateHandler
{
    /**
     * FileSystemReader that reads templates
     *
     * @var FileSystemReader $fileSystemReader
     */
    protected $fileSystemReader;

    /**
     * FileSystemWriter that writes templates
     *
     * @var FileSystemWriter $fileSystemWriter
     */
    protected $fileSystemWriter;

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
        $this->fileSystemReader = new FileSystemReader();
        $this->fileSystemWriter = new FileSystemWriter();
        $this->defaultTemplatesDirectory = $_templatesBaseDirectory;
        $this->customTemplatesDirectory = $_templatesBaseDirectory . "/Custom";
    }
}
