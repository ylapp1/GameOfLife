<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

/**
 * Stores ffmpeg configuration and generates a usable command
 *
 * @package Output
 */
class FfmpegHelper
{
    private $binaryPath;
    private $options = array();


    /**
     * FfmpegHelper constructor.
     *
     * @param string $_binaryPath   Path to the ffmpeg binary file
     */
    public function __construct(string $_binaryPath)
    {
        $this->binaryPath = $_binaryPath;
    }


    /**
     * Returns the ffmpeg binary path
     *
     * @return string   Path to the ffmpeg binary file
     */
    public function binaryPath()
    {
        return $this->binaryPath;
    }

    /**
     * Sets the ffmpeg binary path
     *
     * @param string $_binaryPath   Path to the ffmpeg binary file
     */
    public function setBinaryPath(string $_binaryPath)
    {
        $this->binaryPath = $_binaryPath;
    }

    /**
     * Returns the ffmpeg option list
     *
     * @return array    Ffmpeg option list
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Sets the ffmpeg option list
     *
     * @param array $_options   Ffmpeg option list
     */
    public function setOptions(array $_options)
    {
        $this->options = $_options;
    }


    /**
     * Add an option to the option list
     *
     * @param string $_option   The new option in the format "-<option> <value>"
     */
    public function addOption(string $_option)
    {
        $this->options[] = $_option;
    }

    /**
     * Resets the option list to an empty array
     */
    public function resetOptions()
    {
        $this->options = array();
    }

    /**
     * Generates a ffmpeg command that can be executed by using exec
     *
     * @param string $_outputPath   Ffmpeg output path
     *
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