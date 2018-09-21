<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Util\FileSystem;

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

    // Create

    /**
     * Creates a directory if it doesn't exist yet.
     *
     * @param String $_directoryPath The directory path
     *
     * @throws \Exception The exception when the directory already exists
     * @throws \Exception The exception when the directory was not created
     */
    public function createDirectory(String $_directoryPath)
    {
        $directoryPath = $this->normalizePathDirectorySeparators($_directoryPath);
        if (file_exists($directoryPath))
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" already exists.");
        }

        // Create all directories in the directory path recursively (if they don't exist)
        $directoryWasCreated = mkdir($directoryPath, 0777, true);
        if (! $directoryWasCreated)
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" could not be created.");
        }
    }

    /**
     * Creates a file if it doesn't exist yet.
     *
     * @param String $_filePath The file path
     *
     * @throws \Exception The exception when the file already exists
     * @throws \Exception The exception when the directory was not created
     * @throws \Exception The exception when the file was not created
     */
    public function createFile(String $_filePath)
    {
        $filePath = $this->normalizePathDirectorySeparators($_filePath);
        if (file_exists($filePath))
        {
            throw new \Exception("The file \"" . $filePath . "\" already exists.");
        }

        $directoryPath = dirname($filePath);

        // Create directory if it doesn't exist
        if (! file_exists($directoryPath)) $this->createDirectory($directoryPath);

        $fileWasCreated = touch($filePath);
        if (! $fileWasCreated) throw new \Exception("The file \"" . $filePath . "\" could not be created.");
    }

    // Delete

    /**
     * Deletes a directory.
     *
     * @param String $_directoryPath The directory path
     * @param Bool $_deleteWhenNotEmpty If set to true all files inside the directory will be deleted recursively
     *
     * @throws \Exception The exception when the directory does not exist
     * @throws \Exception The exception when the directory is no directory
     * @throws \Exception The exception when a file or directory inside the directory was not deleted
     * @throws \Exception The exception when the directory is not empty and shall not be deleted in that case
     * @throws \Exception The exception when the directory was not deleted
     */
    public function deleteDirectory(String $_directoryPath, Bool $_deleteWhenNotEmpty = false)
    {
        $directoryPath = $this->normalizePathDirectorySeparators($_directoryPath);
        if (! file_exists($_directoryPath))
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" does not exist.");
        }
        elseif (! is_dir($directoryPath))
        {
            throw new \Exception("\"" . $directoryPath . "\" is no directory or can not be accessed.");
        }

        if ($this->fileSystemReader->getFileList($directoryPath) != array())
        {
            if ($_deleteWhenNotEmpty) $this->deleteFilesInDirectory($directoryPath, true);
            else throw new \Exception("The directory \"" . $_directoryPath . "\" is not empty");
        }

        $directoryDeleted = rmdir($directoryPath);
        if (! $directoryDeleted)
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" could not be deleted.");
        }
    }

    /**
     * Deletes all files in a directory.
     *
     * @param String $_directoryPath The directory path
     * @param Bool $_deleteNonEmptySubDirectories If set to true non empty subdirectories will be deleted too
     *
     * @throws \Exception The exception when the directory does not exist
     * @throws \Exception The exception when a sub directory is not empty and shall not be deleted in that case
     * @throws \Exception The exception when a sub directory was not deleted
     * @throws \Exception The exception when a file was not deleted
     */
    public function deleteFilesInDirectory(String $_directoryPath, Bool $_deleteNonEmptySubDirectories = false)
    {
        $directoryPath = $this->normalizePathDirectorySeparators($_directoryPath);
        $filePaths = $this->fileSystemReader->getFileList($directoryPath);

        foreach ($filePaths as $filePath)
        {
            if (is_dir($filePath)) $this->deleteDirectory($filePath, $_deleteNonEmptySubDirectories);
            else $this->deleteFile($filePath);
        }
    }

    /**
     * Deletes a file.
     *
     * @param String $_filePath The file path
     *
     * @throws \Exception The exception when the file does not exist
     * @throws \Exception The exception when the file was not deleted
     */
    public function deleteFile(String $_filePath)
    {
        $filePath = $this->normalizePathDirectorySeparators($_filePath);
        if (! file_exists($filePath))
        {
            throw new \Exception("The file \"" . $filePath . "\" does not exist.");
        }
        elseif (! is_file($filePath))
        {
            throw new \Exception("\"" . $filePath . "\" is no file or can not be accessed.");
        }

        $fileWasDeleted = unlink($filePath);
        if (! $fileWasDeleted)
        {
            throw new \Exception("The file \"" . $filePath . "\" could not be deleted.");
        }
    }

    // Edit

    /**
     * Writes text to a file.
     * Will create the file if it doesn't exist.
     *
     * @param String $_filePath The file path
     * @param String $_content The file content
     * @param Bool $_appendToFile If set to true the content will be appended to the file if it already exists
     * @param Bool $_overwriteIfExists If set to true the file will be overwritten if it already exists
     *
     * @throws \Exception The exception when the file is no file
     * @throws \Exception The exception when the file already exists and shall not be overwritten in that case
     * @throws \Exception The exception when the file was not deleted
     * @throws \Exception The exception when the directory was not created
     * @throws \Exception The exception when the file was not created
     * @throws \Exception The exception when the file is not writable
     * @throws \Exception The exception when the content was not written to the file
     */
    public function writeFile(String $_filePath, String $_content, Bool $_appendToFile = false, Bool $_overwriteIfExists = false)
    {
        $filePath = $this->normalizePathDirectorySeparators($_filePath);

        $flags = 0;
        if (file_exists($filePath))
        {
            if (! is_file($filePath))
            {
                throw new \Exception("\"" . $filePath . "\" is no file or can not be accessed.");
            }

            if ($_appendToFile) $flags = FILE_APPEND;
            elseif (! $_overwriteIfExists) throw new \Exception("The file \"" . $filePath . "\" already exists.");
            else
            {
                $this->deleteFile($filePath);
                $this->createFile($filePath);
            }
        }
        else $this->createFile($filePath);

        if (! is_writable($filePath)) throw new \Exception("The file \"" . $filePath . "\" is not writable.");

        $contentWasWrittenToFile = file_put_contents($filePath, $_content, $flags);
        if ($contentWasWrittenToFile === false)
        {
            throw new \Exception("The content could not be written to the file \"" . $filePath . "\".");
        }
    }
}
