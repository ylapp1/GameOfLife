<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;
use Utils\FileSystemHandler;
use Utils\ShellExecutor;

/**
 * Stores ffmpeg configuration and generates a usable command.
 *
 * @package Output
 */
class FfmpegHelper
{
    private $binaryPath;
    private $fileSystemHandler;
    private $options = array();
    private $osName;
    private $shellExecutor;


    /**
     * FfmpegHelper constructor.
     *
     * @param String $_osName The name of the operating system
     */
    public function __construct(String $_osName)
    {
        $this->osName = strtolower($_osName);
        $this->fileSystemHandler = new FileSystemHandler();
        $this->shellExecutor = new ShellExecutor($_osName);
        $this->binaryPath = $this->findFFmpegBinary();
    }


    private function findFFmpegBinary()
    {
        $binaryPath = false;

        if (stristr($this->osName, "win"))
        { // If OS is Windows search the Tools directory for the ffmpeg.exe file
            $binaryPath = $this->fileSystemHandler->findFileRecursive(__DIR__ . "/../../../../Tools", "ffmpeg.exe");
        }
        elseif ($this->osName == "linux")
        { // If OS is Linux check whether the ffmpeg command returns true

            $returnValue = $this->shellExecutor->executeCommand("ffmpeg", true);
            if ($returnValue == 1) $binaryPath = "ffmpeg";
        }

        return $binaryPath;
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
     * @return bool|int The return value of the ffmpeg command
     */
    public function executeCommand(String $_outputPath)
    {
        $error = $this->shellExecutor->executeCommand($this->generateCommand($_outputPath), true);

        return $error;
    }
}
