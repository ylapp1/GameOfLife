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

    public function __construct($_binaryPath)
    {
        $this->binaryPath = $_binaryPath;
    }

    /**
     * @return mixed
     */
    public function binaryPath()
    {
        return $this->binaryPath;
    }

    /**
     * @param mixed $_binaryPath
     */
    public function setBinaryPath($_binaryPath)
    {
        $this->binaryPath = $_binaryPath;
    }

    /**
     * @return mixed
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * @param mixed $_options
     */
    public function setOptions($_options)
    {
        $this->options = $_options;
    }

    /**
     * @return mixed
     */
    public function outputPath()
    {
        return $this->outputPath;
    }

    /**
     * @param mixed $_outputPath
     */
    public function setOutputPath($_outputPath)
    {
        $this->outputPath = $_outputPath;
    }


    public function addOption($_option)
    {
        $this->options[] = $_option;
    }

    public function resetOptions()
    {
        $this->options = array();
    }

    public function executeCommand($outputPath)
    {
        // generate command
        $command = "\"" . $this->binaryPath . "\"";
        foreach ($this->options as $option)
        {
            $command .= " " . $option;
        }
        $command .= " \"" . $outputPath . "\"";

        $output = array();
        $returnValue = 0;
        exec($command, $output, $returnValue);
    }
}