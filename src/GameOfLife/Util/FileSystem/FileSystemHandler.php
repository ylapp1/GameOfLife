<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Util\FileSystem;

use Util\OsInformationFetcher;

/**
 * Parent class for FileSystemWriter and FileSystemReader.
 */
abstract class FileSystemHandler
{
    // Attributes

    /**
     * The directory separator symbol in Linux
     *
     * @var String $directorySeparatorSymbolLinux
     */
    private $directorySeparatorSymbolLinux = "/";

    /**
     * The directory separator symbol in Windows
     *
     * @var String $directorySeparatorSymbolWindows
     */
    private $directorySeparatorSymbolWindows = "\\";

    /**
     * The cached directory separator symbol for this operating system
     *
     * @var String $directorySeparatorSymbol
     */
    protected $directorySeparatorSymbol;

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
            $this->directorySeparatorSymbol = $this->directorySeparatorSymbolWindows;
        }
        else $this->directorySeparatorSymbol = $this->directorySeparatorSymbolLinux;
    }


    // Class Methods

    /**
     * Converts all directory separators in a path to the os specific type.
     *
     * Windows: All forward slashes are converted to backslashes
     * Linux: All backslashes are converted to forward slashes
     *
     * @param String $_path The path to a file or directory with multiple directory separator types
     *
     * @return String The path with only the os specific directory separator type
     */
    protected function normalizePathDirectorySeparators(String $_path): String
    {
        if ($this->osInformationFetcher->isWindows())
        {
            return str_replace($this->directorySeparatorSymbolLinux, $this->directorySeparatorSymbolWindows, $_path);
        }
        elseif ($this->osInformationFetcher->isLinux())
        {
            return str_replace($this->directorySeparatorSymbolWindows, $this->directorySeparatorSymbolLinux, $_path);
        }
        else return $_path;
    }
}
