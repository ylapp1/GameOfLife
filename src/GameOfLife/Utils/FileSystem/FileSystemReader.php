<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\FileSystem;

/**
 * Provides methods to read files and directories from the file system.
 */
class FileSystemReader extends FileSystemHandler
{
    // Magic Methods

    /**
     * FileSystemReader constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    // Class Methods

    /**
     * Searches a directory and its subdirectories for a specific file name and returns the path to the file.
     * The file name comparison is case sensitive.
     *
     * @param String $_searchDirectoryPath The path to the directory that will be searched for the file name
     * @param String $_fileName The file name
     *
     * @return String|null The file path or null if the file was not found
     *
     * @throws \Exception The exception when the search directory does not exist
     */
    public function findFileRecursive(String $_searchDirectoryPath, String $_fileName)
    {
        $searchDirectory = $this->normalizePathFileSeparators($_searchDirectoryPath);

        if (! file_exists($searchDirectory) || ! is_dir($searchDirectory))
        {
            throw new \Exception("The directory \"" . $searchDirectory . "\" does not exist or can not be accessed.");
        }

        $directoryIterator = new \RecursiveDirectoryIterator($searchDirectory);
        foreach (new \RecursiveIteratorIterator($directoryIterator) as $filePath)
        {
            if (basename($filePath) == $_fileName) return $this->normalizePathFileSeparators($filePath);
        }

        return null;
    }

    /**
     * Returns a list of file paths of all files in a directory with a specific pattern.
     *
     * @param String $_directoryPath The directory path
     * @param String $_searchPattern The custom search pattern
     *
     * @return String[] The list of file paths
     *
     * @throws \Exception The exception when the target directory does not exist
     */
    public function getFileList(String $_directoryPath, String $_searchPattern = "*"): array
    {
        $directoryPath = $this->normalizePathFileSeparators($_directoryPath);

        if (! file_exists($directoryPath) || ! is_dir($directoryPath))
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" does not exist or can not be accessed.");
        }
        else return glob($directoryPath . $this->fileSeparatorSymbol . $_searchPattern);
    }

    /**
     * Reads and returns the contents of a file.
     *
     * @param String $_filePath The file path
     *
     * @return String[] The contents of the file as a list of lines
     *
     * @throws \Exception The exception when the target file does not exist
     */
    public function readFile(String $_filePath): array
    {
        $filePath = $this->normalizePathFileSeparators($_filePath);

        if (! file_exists($filePath) || ! is_file($filePath))
        {
            throw new \Exception("The file \"" . $filePath . "\" does not exist or can not be accessed.");
        }
        else return file($filePath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    }
}
