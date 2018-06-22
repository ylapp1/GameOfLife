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
abstract class TemplateHandler
{
    // Attributes

    /**
     * The file system reader
     *
     * @var FileSystemReader $fileSystemReader
     */
    protected $fileSystemReader;

    /**
     * The file system writer
     *
     * @var FileSystemWriter $fileSystemWriter
     */
    protected $fileSystemWriter;

    /**
     * The directory in which the default templates are stored
     *
     * @var String $defaultTemplatesDirectory
     */
    protected $defaultTemplatesDirectory;

    /**
     * The directory in which the custom templates are stored
     * This is always the sub directory "Custom" of the default templates directory
     *
     * @var String $customTemplatesDirectory
     */
    protected $customTemplatesDirectory;


    // Magic Methods

    /**
     * TemplateHandler constructor.
     *
     * @param String $_defaultTemplatesDirectory The directory in which the default templates are stored
     */
    protected function __construct(String $_defaultTemplatesDirectory)
    {
        $this->fileSystemReader = new FileSystemReader();
        $this->fileSystemWriter = new FileSystemWriter();
        $this->defaultTemplatesDirectory = $_defaultTemplatesDirectory;
        $this->customTemplatesDirectory = $_defaultTemplatesDirectory . "/Custom";
    }
}
