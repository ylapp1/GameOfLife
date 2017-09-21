<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils;

/**
 * Class FileSystemHandler
 *
 * @package Output\Helpers
 */
class FileSystemHandler
{
    const NO_ERROR = 0;
    const ERROR_DIRECTORY_EXISTS = 1;
    const ERROR_DIRECTORY_NOT_EMPTY = 2;
    const ERROR_DIRECTORY_NOT_EXISTS = 3;
    const ERROR_FILE_EXISTS = 4;
    const ERROR_FILE_NOT_EXISTS = 5;

    /**
     * Creates a directory if it doesn't exist yet
     *
     * @param string $_directoryPath    The directory path
     *
     * @return int  Error
     */
    public function createDirectory(string $_directoryPath)
    {
        if (! file_exists($_directoryPath))
        {
            // create all directories in the directory path (if they don't exist)
            mkdir($_directoryPath, 0777, true);
            return self::NO_ERROR;
        }
        else return self::ERROR_DIRECTORY_EXISTS;
    }

    /**
     * Deletes a directory
     *
     * @param string $_directoryPath        The directory path
     * @param bool $_deleteWhenNotEmpty     If set to true all files inside the directory will be deleted
     *
     * @return int  Error
     */
    public function deleteDirectory(string $_directoryPath, bool $_deleteWhenNotEmpty = false)
    {
        if (! file_exists($_directoryPath)) return self::ERROR_DIRECTORY_NOT_EXISTS;

        if (substr($_directoryPath, strlen($_directoryPath) - 1, 1) != '/') $_directoryPath .= '/';
        $files = glob($_directoryPath . '*', GLOB_MARK);

        if (count($files) !== 0)
        {
            if (! $_deleteWhenNotEmpty) return self::ERROR_DIRECTORY_NOT_EMPTY;
            else
            {
                foreach ($files as $file)
                {
                    if (is_dir($file))
                    {
                        $error = $this->deleteDirectory($file, $_deleteWhenNotEmpty);
                        if ($error != self::NO_ERROR) return $error;
                    }
                    else unlink($file);
                }
            }
        }

        rmdir($_directoryPath);
        return self::NO_ERROR;
    }

    /**
     * Deletes a file
     *
     * @param string $_filePath    Path to the file that shall be deleted
     *
     * @return int  Error
     */
    function deleteFile(string $_filePath)
    {
        if (file_exists($_filePath))
        {
            unlink($_filePath);
            return self::NO_ERROR;
        }
        else return self::ERROR_FILE_NOT_EXISTS;
    }

    /**
     * Read text from file
     *
     * @param string $_filePath     The file path
     *
     * @return array|int            File Content or Error
     */
    function readFile(string $_filePath)
    {
        if (! file_exists($_filePath)) return $this::ERROR_FILE_NOT_EXISTS;
        else return file($_filePath, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    }

    /**
     * Write text to file
     *
     * @param string $_filePath         The file path
     * @param string $_fileName         The name of the new file
     * @param string $_content          The file content
     * @param bool $_overwriteIfExists  Overwrite existing file
     *
     * @return int  Error
     */
    function writeFile(string $_filePath, string $_fileName, string $_content, bool $_overwriteIfExists = false)
    {
        // Create directory if it doesn't exist
        if (file_exists($_filePath . "/" . $_fileName))
        {
            if (! $_overwriteIfExists) return self::ERROR_FILE_EXISTS;
            else $this->deleteFile($_filePath . "/" . $_fileName);
        }
        else $this->createDirectory($_filePath);

        file_put_contents($_filePath . "/" . $_fileName, $_content);

        return self::NO_ERROR;
    }
}