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
 * Parent class for TemplateLoader and TemplateSaver.
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


    /**
     * Returns the file system.
     *
     * @return FileSystemHandler The file system handler
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the file system handler.
     *
     * @param FileSystemHandler $_fileSystemHandler The file system handler
     */
    public function setFileSystemHandler(FileSystemhandler $_fileSystemHandler)
    {
        $this->fileSystemHandler = $_fileSystemHandler;
    }

    /**
     * Returns the directory for default templates.
     *
     * @return String The directory for default templates
     */
    public function defaultTemplatesDirectory(): String
    {
        return $this->defaultTemplatesDirectory;
    }

    /**
     * Sets the directory for default templates.
     *
     * @param String $_defaultTemplatesDirectory The directory for default templates
     */
    public function setDefaultTemplatesDirectory(String $_defaultTemplatesDirectory)
    {
        $this->defaultTemplatesDirectory = $_defaultTemplatesDirectory;
    }

    /**
     * Returns the directory for custom templates.
     *
     * @return String The directory for custom templates
     */
    public function customTemplatesDirectory(): String
    {
        return $this->customTemplatesDirectory;
    }

    /**
     * Sets the directory for custom templates.
     *
     * @param String $_customTemplatesDirectory The directory for custom templates
     */
    public function setCustomTemplatesDirectory(String $_customTemplatesDirectory)
    {
        $this->customTemplatesDirectory = $_customTemplatesDirectory;
    }
}
