<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

/**
 * Class FfmpegHelper
 *
 * Helper for using ffmpeg in VideoOutput
 *
 * @package Output
 */
class FfmpegHelper
{
    private $binaryPath;
    private $options = array();
    private $outputPath;

    public function __construct(string $_binaryPath)
    {
        $this->binaryPath = $_binaryPath;
    }

    /**
     * @return string
     */
    public function binaryPath()
    {
        return $this->binaryPath;
    }

    /**
     * @param string $_binaryPath
     */
    public function setBinaryPath(string $_binaryPath)
    {
        $this->binaryPath = $_binaryPath;
    }

    /**
     * @return array
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * @param array $_options
     */
    public function setOptions(array $_options)
    {
        $this->options = $_options;
    }

    /**
     * @return string
     */
    public function outputPath()
    {
        return $this->outputPath;
    }

    /**
     * @param string $_outputPath
     */
    public function setOutputPath(string $_outputPath)
    {
        $this->outputPath = $_outputPath;
    }

    /**
     * Add an option to the option list
     *
     * @param string $_option
     */
    public function addOption(string $_option)
    {
        $this->options[] = $_option;
    }

    /**
     * Reset the option list to an empty array
     */
    public function resetOptions()
    {
        $this->options = array();
    }

    /**
     * Generates a ffmpeg command that can be executed by using exec
     *
     * @param string $_outputPath   Path where result of ffmpeg shall be saved
     * @return string               The ffmpeg command
     */
    public function generateCommand(string $_outputPath)
    {
        $command = "\"" . $this->binaryPath . "\"";
        foreach ($this->options as $option)
        {
            $command .= " " . $option;
        }
        $command .= " \"" . $_outputPath . "\"";
        // hide output by redirecting it to NUL
        $command .= " 2>NUL";

        return $command;
    }
}