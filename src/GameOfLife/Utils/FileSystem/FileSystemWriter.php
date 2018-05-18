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
    // Attributes

    /**
     * The file system reader
     *
     * @var FileSystemReader $fileSystemReader
     */
    private $fileSystemReader;


    // Magic Methods

    /**
     * FileSystemWriter constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileSystemReader = new FileSystemReader();
    }


    // Class Methods

    /**
     * Creates a directory if it doesn't exist yet.
     *
     * @param String $_directoryPath The directory path
     *
     * @throws \Exception The exception when the target directory already exists
     */
    public function createDirectory(String $_directoryPath)
    {
        $directoryPath = $this->normalizePathFileSeparators($_directoryPath);

        if (! file_exists($directoryPath))
        {
            // Create all directories in the directory path recursively (if they don't exist)
            mkdir($directoryPath, 0777, true);

            if (! file_exists($directoryPath))
            {
                throw new \Exception("The directory \"" . $directoryPath . "\" could not be created.");
            }
        }
        else throw new \Exception("The directory \"" . $directoryPath . "\" already exists.");
    }

    /**
     * Deletes a directory.
     *
     * @param String $_directoryPath The directory path
     * @param Bool $_deleteWhenNotEmpty If set to true all files inside the directory will be deleted recursively
     *
     * @throws \Exception The exception when the target directory does not exist or is not empty and shall not be deleted in that case
     */
    public function deleteDirectory(String $_directoryPath, Bool $_deleteWhenNotEmpty = false)
    {
        $directoryPath = $this->normalizePathFileSeparators($_directoryPath);
        if (! file_exists($directoryPath) || ! is_dir($directoryPath))
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" does not exist or can not be accessed.");
        }

        if ($this->fileSystemReader->getFileList($directoryPath) != array())
        {
            if (! $_deleteWhenNotEmpty) throw new \Exception("The directory \"" . $_directoryPath . "\" is not empty");
            else $this->deleteFilesInDirectory($directoryPath, true);
        }

        rmdir($directoryPath);

        if (file_exists($directoryPath))
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" could not be deleted.");
        }
    }

    /**
     * Deletes all files in a directory.
     *
     * @param String $_directoryPath The directory path
     * @param bool $_deleteNonEmptySubDirectories Indicates whether non empty subdirectories should be deleted
     *
     * @throws \Exception The exception when the target directory does not exist
     */
    public function deleteFilesInDirectory(String $_directoryPath, bool $_deleteNonEmptySubDirectories = false)
    {
        $directoryPath = $this->normalizePathFileSeparators($_directoryPath);
        $files = $this->fileSystemReader->getFileList($directoryPath);

        foreach ($files as $file)
        {
            if (is_dir($file)) $this->deleteDirectory($file, $_deleteNonEmptySubDirectories);
            else unlink($file);
        }

        if ($this->fileSystemReader->getFileList($directoryPath) !== array())
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
        $filePath = $this->normalizePathFileSeparators($_filePath);

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
        $filePath = $this->normalizePathFileSeparators($_filePath);
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
