<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils;

/**
 * Handles directory and file creation/deletion.
 *
 * @package Output\Helpers
 */
class FileSystemHandler
{
    /**
     * Creates a directory if it doesn't exist yet.
     *
     * @param string $_directoryPath The directory path
     *
     * @throws \Exception The exception when the target directory already exists
     */
    public function createDirectory(string $_directoryPath)
    {
        $directoryPath = $this->convertSlashes($_directoryPath);

        if (! file_exists($directoryPath))
        {
            // create all directories in the directory path recursively (if they don't exist)
            mkdir($directoryPath, 0777, true);
        }
        else throw new \Exception("The directory \"" . $directoryPath . "\" already exists.");
    }

    /**
     * Deletes a directory.
     *
     * @param string $_directoryPath The directory path
     * @param bool $_deleteWhenNotEmpty If set to true all files inside the directory will be deleted
     *
     * @throws \Exception The exception when the target directory does not exist or is not empty and shall not be deleted in that case
     */
    public function deleteDirectory(string $_directoryPath, bool $_deleteWhenNotEmpty = false)
    {
        $directoryPath = $this->convertSlashes($_directoryPath);
        if (! file_exists($directoryPath)) throw new \Exception("The directory \"" . $directoryPath . "\" does not exist.");

        $files = $this->getFileList($directoryPath . "/*");

        if (count($files) !== 0)
        {
            if (! $_deleteWhenNotEmpty) throw new \Exception("The directory \"" . $_directoryPath . "\" is not empty");
            else $this->deleteFilesInDirectory($directoryPath, true);
        }

        rmdir($directoryPath);
    }

    /**
     * Deletes all files in a directory without deleting the directory.
     *
     * @param String $_directoryPath The path to the directory
     * @param bool $_deleteNonEmptySubDirectories Indicates whether non empty subdirectories should be deleted
     *
     * @throws \Exception The exception when the target directory does not exist
     */
    public function deleteFilesInDirectory(String $_directoryPath, bool $_deleteNonEmptySubDirectories = false)
    {
        $directoryPath = $this->convertSlashes($_directoryPath);
        $files = $this->getFileList($directoryPath . "/*");

        foreach ($files as $file)
        {
            if (is_dir($file)) $this->deleteDirectory($file, $_deleteNonEmptySubDirectories);
            else unlink($file);
        }
    }

    /**
     * Deletes a file.
     *
     * @param string $_filePath The path to the file that will be deleted
     *
     * @throws \Exception The exception when the target file does not exist
     */
    public function deleteFile(string $_filePath)
    {
        $filePath = $this->convertSlashes($_filePath);

        if (file_exists($filePath)) unlink($filePath);
        else throw new \Exception("The file \"" . $filePath . "\" does not exist.");
    }

    /**
     * Read text from file.
     *
     * @param string $_filePath The path to the file that will be read
     *
     * @return String[] The lines from the file
     *
     * @throws \Exception The exception when the target file does not exist
     */
    public function readFile(string $_filePath): array
    {
        $filePath = $this->convertSlashes($_filePath);

        if (! file_exists($filePath)) throw new \Exception("The file \"" . $filePath . "\" does not exist.");
        else return file($filePath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    }

    /**
     * Writes text to a file.
     * Will create the file if it doesn't exist
     *
     * @param string $_filePath The file path
     * @param string $_content The file content
     * @param Bool  $_appendToFile Indicates whether the content will be appended to the file
     * @param bool $_overwriteIfExists If set to true, an existing file will be overwritten
     *
     * @throws \Exception The exception when the file already exists and shall not be overwritten in that case
     */
    public function writeFile(string $_filePath, string $_content, Bool $_appendToFile = false, bool $_overwriteIfExists = false)
    {
        $filePath = $this->convertSlashes($_filePath);
        $directoryPath = dirname($filePath);

        // Create directory if it doesn't exist
        if (! file_exists($directoryPath)) $this->createDirectory($directoryPath);

        $flags = 0;

        if (file_exists($filePath))
        {
            if ($_appendToFile) $flags = FILE_APPEND;
            elseif (! $_overwriteIfExists) throw new \Exception("The file already exists.");
            else $this->deleteFile($filePath);
        }

        file_put_contents($filePath, $_content, $flags);
    }

    /**
     * Returns an array of files in a directory.
     *
     * @param string $_directoryPath The directory of which a file list will be returned
     *
     * @return array The file list
     *
     * @throws \Exception The exception when the target directory does not exist
     */
    public function getFileList(String $_directoryPath): array
    {
        $filePath = $this->convertSlashes($_directoryPath);
        $directoryName = dirname($filePath);

        if (! file_exists($directoryName)) throw new \Exception("The directory \"" . $directoryName . "\" does not exist.");
        else return glob($filePath);
    }

    /**
     * Searches a folder for a file.
     *
     * @param String $_baseFolder The folder path
     * @param String $_fileName The file name
     *
     * @return String|bool The file path or false
     *
     * @throws \Exception The exception when the target directory does not exist
     */
    public function findFileRecursive(String $_baseFolder, String $_fileName)
    {
        $baseFolder = $this->convertSlashes($_baseFolder);
        if (! is_dir(dirname($baseFolder))) throw new \Exception("The directory \"" . $baseFolder . "\" does not exist.");

        $directoryIterator = new \RecursiveDirectoryIterator($baseFolder);

        foreach (new \RecursiveIteratorIterator($directoryIterator) as $file)
        {
            if (strtolower(basename($file)) == strtolower($_fileName))
            {
                return $this->convertSlashes($file);
            }
        }

        return false;
    }

    /**
     * Converts the slashes to backslashes (Windows) or the backslashes to slashes (Linux).
     *
     * @param String $_path The path with mixed slashes and backslashes
     *
     * @return String The path with either only slashes or only backslashes
     */
    private function convertSlashes(String $_path): String
    {
        if (stristr(PHP_OS, "win")) return str_replace("/", "\\", $_path);
        elseif (stristr(PHP_OS, "linux")) return str_replace("\\", "/", $_path);
        else return $_path;
    }
}
