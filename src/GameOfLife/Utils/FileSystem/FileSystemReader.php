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
     * @throws \Exception The exception when the directory does not exist
     * @throws \Exception The exception when the directory is not readable
     * @throws \Exception The exception when the directory is no directory
     */
    public function findFileRecursive(String $_searchDirectoryPath, String $_fileName)
    {
        $directoryPath = $this->normalizePathFileSeparators($_searchDirectoryPath);
        $this->checkPath($directoryPath, "directory");

        $filePaths = $this->getFileList($directoryPath);
        foreach ($filePaths as $filePath)
        {
            if (is_dir($filePath))
            {
                $resultFilePath = $this->findFileRecursive($filePath, $_fileName);
                if ($resultFilePath !== null) return $resultFilePath;
            }
            elseif (basename($filePath) == $_fileName) return $this->normalizePathFileSeparators($filePath);
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
     * @throws \Exception The exception when the directory does not exist
     * @throws \Exception The exception when the directory is not readable
     * @throws \Exception The exception when the directory is no directory
     */
    public function getFileList(String $_directoryPath, String $_searchPattern = "*"): array
    {
        $directoryPath = $this->normalizePathFileSeparators($_directoryPath);
        $this->checkPath($directoryPath, "directory");

        $fileList = glob($directoryPath . $this->fileSeparatorSymbol . $_searchPattern);
        if ($fileList === false)
        {
            throw new \Exception("The directory \"" . $directoryPath . "\" could not be read.");
        }

        return $fileList;
    }

    /**
     * Reads and returns the contents of a file.
     *
     * @param String $_filePath The file path
     *
     * @return String[] The contents of the file as a list of lines
     *
     * @throws \Exception The exception when the file does not exist
     * @throws \Exception The exception when the file is not readable
     * @throws \Exception The exception when the file is no file
     */
    public function readFile(String $_filePath): array
    {
        $filePath = $this->normalizePathFileSeparators($_filePath);
        $this->checkPath($filePath, "file");

        $fileContent = file($filePath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        if ($fileContent === false)
        {
            throw new \Exception("The file \"" . $filePath . "\" could not be read.");
        }

        return $fileContent;
    }

    /**
     * Checks whether a path to a file or directory is valid.
     *
     * @param String $_path The path to the directory or file
     * @param String $_pathType The path type (Possible values: directory, file)
     *
     * @throws \Exception The exception when the directory or file does not exist
     * @throws \Exception The exception when the directory or file is not readable
     * @throws \Exception The exception when the directory is no directory or the file is no file
     */
    private function checkPath(String $_path, String $_pathType)
    {
        if (! file_exists($_path))
        {
            throw new \Exception("The " . $_pathType . " \"" . $_path . "\" does not exist.");
        }
        elseif (! is_readable($_path))
        {
            throw new \Exception("The " . $_pathType . " \"" . $_path . "\" is not readable.");
        }
        elseif ($_pathType == "directory" && ! is_dir($_path) ||
                $_pathType == "file" && ! is_file($_path))
        {
            throw new \Exception("\"" . $_path . "\" is no " . $_pathType . " or can not be accessed.");
        }
    }
}
