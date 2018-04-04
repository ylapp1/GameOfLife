<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

use Utils\FileSystem\FileSystemReader;
use Utils\FileSystem\FileSystemWriter;
use Utils\Shell\ShellExecutor;

/**
 * Stores ffmpeg configuration and generates a usable command.
 *
 * @package Output
 */
class FfmpegHelper
{
    private $binaryPath;
    private $fileSystemReader;
    private $fileSystemWriter;
    private $options = array();
    private $osName;
    private $shellExecutor;


    /**
     * FfmpegHelper constructor.
     *
     * @param String $_osName The name of the operating system
     *
     * @throws \Exception The exception when the ffmpeg binary could not be found
     */
    public function __construct(String $_osName)
    {
        $this->osName = strtolower($_osName);
        $this->fileSystemReader = new FileSystemReader();
        $this->fileSystemWriter = new FileSystemWriter();
        $this->shellExecutor = new ShellExecutor();

        $this->binaryPath = $this->findFFmpegBinary();
    }


    /**
     * Returns the ffmpeg binary path.
     *
     * @return string   Path to the ffmpeg binary file
     */
    public function binaryPath(): string
    {
        return $this->binaryPath;
    }

    /**
     * Sets the ffmpeg binary path.
     *
     * @param string $_binaryPath   Path to the ffmpeg binary file
     */
    public function setBinaryPath(string $_binaryPath)
    {
        $this->binaryPath = $_binaryPath;
    }

    public function fileSystemHandler(): FileSystemReader
    {
        return $this->fileSystemReader;
    }

    public function setFileSystemHandler(FileSystemReader $_fileSystemReader)
    {
        $this->fileSystemReader = $_fileSystemReader;
    }

    /**
     * Returns the ffmpeg option list.
     *
     * @return array    Ffmpeg option list
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * Sets the ffmpeg option list.
     *
     * @param array $_options   Ffmpeg option list
     */
    public function setOptions(array $_options)
    {
        $this->options = $_options;
    }

    public function shellExecutor()
    {
        return $this->shellExecutor;
    }

    public function setShellExecutor(ShellExecutor $_shellExecutor)
    {
        $this->shellExecutor = $_shellExecutor;
    }


    /**
     * Finds and returns the path to the ffmpeg binary file.
     *
     * @return String The path to the ffmpeg binary file
     *
     * @throws \Exception The exception when the ffmpeg binary could not be found
     */
    private function findFFmpegBinary()
    {
        $binaryPath = false;

        if (stristr($this->osName, "win"))
        { // If OS is Windows search the Tools directory for the ffmpeg.exe file
            $searchDirectory = __DIR__ . "/../../../../Tools";
            $binaryPath = $this->fileSystemReader->findFileRecursive($searchDirectory, "ffmpeg.exe");

            if (! $binaryPath) throw new \Exception("The ffmpeg.exe file could not be found in \"" . $searchDirectory . "\".");
        }
        elseif (stristr($this->osName, "linux"))
        { // If OS is Linux check whether the ffmpeg command returns true
            $returnValue = $this->shellExecutor->executeCommand("ffmpeg", true);
            if ($returnValue == 1) $binaryPath = "ffmpeg";
        }

        return $binaryPath;
    }

    /**
     * Add an option to the option list.
     *
     * @param string $_option   The new option in the format "-<option> <value>"
     */
    public function addOption(string $_option)
    {
        $this->options[] = $_option;
    }

    /**
     * Resets the option list to an empty array.
     */
    public function resetOptions()
    {
        $this->options = array();
    }

    /**
     * Generates a ffmpeg command that can be executed by using exec.
     *
     * @param String $_outputPath The Ffmpeg output path
     *
     * @return String The ffmpeg command
     */
    public function generateCommand(String $_outputPath): String
    {
        $command = "";

        if (stristr($this->osName, "win")) $command .= "\"";
        $command .= $this->binaryPath;
        if (stristr($this->osName, "win")) $command .= "\"";

        foreach ($this->options as $option)
        {
            $command .= " " . $option;
        }
        $command .= " \"" . $_outputPath . "\"";

        return $command;
    }

    /**
     * Executes the ffmpeg command.
     *
     * @param String $_outputPath The Ffmpeg output path
     *
     * @throws \Exception The exception when the ffmpeg command returns an error
     */
    public function executeCommand(String $_outputPath)
    {
        $error = $this->shellExecutor->executeCommand($this->generateCommand($_outputPath), true);

        if ($error)
        {
            try
            {
                // Delete the damaged video file (if one was created)
                $this->fileSystemWriter->deleteFile($_outputPath);
            }
            catch (\Exception $_exception)
            {
                // Ignore the exception
            }

            throw new \Exception("Ffmpeg returned the error code \"" . $error . "\".");
        }
    }
}
