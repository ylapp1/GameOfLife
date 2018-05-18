<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\FileSystem;

use Utils\OsInformationFetcher;

/**
 * Parent class for FileSystemWriter and FileSystemReader.
 */
class FileSystemHandler
{
    // Attributes

    /**
     * The file separator symbol in Linux
     *
     * @var String $fileSeparatorSymbolLinux
     */
    protected $fileSeparatorSymbolLinux = "/";

    /**
     * The file separator symbol in Windows
     *
     * @var String $fileSeparatorSymbolWindows
     */
    protected $fileSeparatorSymbolWindows = "\\";

    /**
     * The cached file separator symbol for this operating system.
     *
     * @var String $fileSeparatorSymbol
     */
    protected $fileSeparatorSymbol;

    /**
     * The os information fetcher
     *
     * @var OsInformationFetcher $osInformationFetcher
     */
    private $osInformationFetcher;


    // Magic Methods

    /**
     * FileSystemHandler constructor.
     */
    protected function __construct()
    {
        $this->osInformationFetcher = new OsInformationFetcher();

        if ($this->osInformationFetcher->isWindows())
        {
            $this->fileSeparatorSymbol = $this->fileSeparatorSymbolWindows;
        }
        else $this->fileSeparatorSymbol = $this->fileSeparatorSymbolLinux;
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
        if ($this->osInformationFetcher->isWindows())
        {
            return str_replace($this->fileSeparatorSymbolLinux, $this->fileSeparatorSymbolWindows, $_path);
        }
        elseif ($this->osInformationFetcher->isLinux())
        {
            return str_replace($this->fileSeparatorSymbolWindows, $this->fileSeparatorSymbolLinux, $_path);
        }
        else return $_path;
    }
}
