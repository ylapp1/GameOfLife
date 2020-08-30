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
}
