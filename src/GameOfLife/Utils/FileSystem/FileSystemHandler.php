<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\FileSystem;

/**
 * Parent class for FileSystemWriter and FileSystemReader.
 */
class FileSystemHandler
{
    /**
     * Converts the slashes to backslashes (Windows) or the backslashes to slashes (Linux).
     *
     * @param String $_path The path with mixed slashes and backslashes
     *
     * @return String The path with either only slashes or only backslashes
     */
    protected function convertSlashes(String $_path): String
    {
        if (stristr(PHP_OS, "win")) return str_replace("/", "\\", $_path);
        elseif (stristr(PHP_OS, "linux")) return str_replace("\\", "/", $_path);
        else return $_path;
    }
}
