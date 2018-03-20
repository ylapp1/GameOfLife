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
        if (! file_exists($_directoryPath))
        {
            // create all directories in the directory path recursively (if they don't exist)
            mkdir($_directoryPath, 0777, true);
        }
        else throw new \Exception("The directory \"" . $_directoryPath . "\" already exists.");
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
        if (! file_exists($_directoryPath)) throw new \Exception("The directory \"" . $_directoryPath . "\" does not exist.");

        if (substr($_directoryPath, strlen($_directoryPath) - 1, 1) != '/') $_directoryPath .= '/';
        $files = $this->getFileList($_directoryPath . "/*");

        if (count($files) !== 0)
        {
            if (! $_deleteWhenNotEmpty) throw new \Exception("The directory is not empty");
            else
            {
                foreach ($files as $file)
                {
                    if (is_dir($file)) $this->deleteDirectory($file, $_deleteWhenNotEmpty);
                    else unlink($file);
                }
            }
        }

        rmdir($_directoryPath);
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
        if (file_exists($_filePath)) unlink($_filePath);
        else throw new \Exception("The file \"" . $_filePath . "\" does not exist.");
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
        if (! file_exists($_filePath)) throw new \Exception("The file \"" . $_filePath . "\" does not exist.");
        else return file($_filePath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    }

    /**
     * Write text to file.
     *
     * @param string $_filePath The file path
     * @param string $_fileName The name of the new file
     * @param string $_content The file content
     * @param bool $_overwriteIfExists If set to true, an existing file will be overwritten
     *
     * @throws \Exception The exception when the file already exists and shall not be overwritten in that case
     */
    public function writeFile(string $_filePath, string $_fileName, string $_content, bool $_overwriteIfExists = false)
    {
        // Create directory if it doesn't exist
        if (! file_exists($_filePath)) $this->createDirectory($_filePath);

        if (file_exists($_filePath . "/" . $_fileName))
        {
            if (! $_overwriteIfExists) throw new \Exception("The file already exists.");
            else $this->deleteFile($_filePath . "/" . $_fileName);
        }

        file_put_contents($_filePath . "/" . $_fileName, $_content);
    }

    /**
     * Returns an array of files in a directory.
     *
     * @param string $_filePath The directory of which a file list will be returned
     *
     * @return array The file list
     *
     * @throws \Exception The exception when the target directory does not exist
     */
    public function getFileList(String $_filePath): array
    {
        $filePath = $this->convertSlashes($_filePath);
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
        if (! is_dir(dirname($_baseFolder))) throw new \Exception("The directory \"" . $_baseFolder . "\" does not exist.");

        $directoryIterator = new \RecursiveDirectoryIterator($_baseFolder);

        foreach (new \RecursiveIteratorIterator($directoryIterator) as $file)
        {
            if (stripos($file, $_fileName) == strlen($file) - strlen($_fileName))
            {
                $filePath = str_replace("\\", "/", $file);
                return $filePath;
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
