<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\FileSystem;

use Utils\Shell\ShellInformationFetcher;

/**
 * Parent class for FileSystemWriter and FileSystemReader.
 */
class FileSystemHandler
{
    // Attributes

    /**
     * The shell information fetcher
     *
     * @var ShellInformationFetcher $shellInformationFetcher
     */
    private $shellInformationFetcher;


    // Magic Methods

    /**
     * FileSystemHandler constructor.
     */
    protected function __construct()
    {
        $this->shellInformationFetcher = new ShellInformationFetcher();
    }


    // Class Methods

    /**
     * Converts all file separators in a path to the os specific type.
     *
     * Windows: All forward slashes are converted to backslashes
     * Linux: All backslashes are converted to forward slashes
     *
     * @param String $_path The path to a file or directory with multiple file separator types
     *
     * @return String The path with only the os specific file separator type
     */
    protected function normalizePathFileSeparators(String $_path): String
    {
        if ($this->shellInformationFetcher->getOsType() == ShellInformationFetcher::osWindows)
        {
            return str_replace("/", "\\", $_path);
        }
        elseif ($this->shellInformationFetcher->getOsType() == ShellInformationFetcher::osLinux)
        {
            return str_replace("\\", "/", $_path);
        }
        else return $_path;
    }
}
