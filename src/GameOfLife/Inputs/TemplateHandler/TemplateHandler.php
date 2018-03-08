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
 * Parent class for template loader and saver.
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
     * Base directory from which templates are read
     *
     * @var string $templateDirectory
     */
    protected $templateDirectory;


    /**
     * TemplateLoader constructor.
     *
     * @param String $_templateDirectory Template base directory
     */
    public function __construct(String $_templateDirectory)
    {
        $this->fileSystemHandler = new FileSystemHandler();
        $this->templateDirectory = $_templateDirectory;
    }


    /**
     * Returns the file system handler of this file input.
     *
     * @return FileSystemHandler The file system handler
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the file system handler of this file input.
     *
     * @param FileSystemHandler $_fileSystemHandler     The file system handler
     */
    public function setFileSystemHandler(FileSystemhandler $_fileSystemHandler)
    {
        $this->fileSystemHandler = $_fileSystemHandler;
    }

    /**
     * Returns the template directory.
     *
     * @return string   Template directory
     */
    public function templateDirectory(): string
    {
        return $this->templateDirectory;
    }

    /**
     * Sets the template directory.
     *
     * @param string $_templateDirectory    Template directory
     */
    public function setTemplateDirectory(string $_templateDirectory)
    {
        $this->templateDirectory = $_templateDirectory;
    }
}
