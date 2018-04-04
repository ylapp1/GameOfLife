<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\FileSystem;

/**
 * Provides methods to write files and directories to the file system.
 */
class FileSystemWriter extends FileSystemHandler
{
    /**
     * The file system reader
     *
     * @var FileSystemReader $fileSystemReader
     */
    private $fileSystemReader;


    /**
     * FileSystemWriter constructor.
     */
    public function __construct()
    {
        $this->fileSystemReader = new FileSystemReader();
    }

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

            if (! file_exists($directoryPath))
            {
                throw new \Exception("Unknown error while creating the directory \"" . $directoryPath . "\".");
            }
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

        $files = $this->fileSystemReader->getFileList($directoryPath . "/*");

        if (count($files) !== 0)
        {
            if (! $_deleteWhenNotEmpty) throw new \Exception("The directory \"" . $_directoryPath . "\" is not empty");
            else $this->deleteFilesInDirectory($directoryPath, true);
        }

        rmdir($directoryPath);

        if (file_exists($directoryPath))
        {
            throw new \Exception("Unknown error while deleting the directory \"" . $directoryPath . "\".");
        }
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
        $files = $this->fileSystemReader->getFileList($directoryPath . "/*");

        foreach ($files as $file)
        {
            if (is_dir($file)) $this->deleteDirectory($file, $_deleteNonEmptySubDirectories);
            else unlink($file);
        }

        if ($this->fileSystemReader->getFileList($directoryPath . "/*") !== array())
        {
            throw new \Exception("Unknown error while deleting the files in the directory \"" . $directoryPath . "\".");
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

        if (file_exists($filePath))
        {
            throw new \Exception("Unknown error while deleting the file \"" . $filePath . "\".");
        }
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

        if (! file_exists($filePath))
        {
            throw new \Exception("Unknown error while writing the file \"" . $filePath . "\".");
        }
    }
}
