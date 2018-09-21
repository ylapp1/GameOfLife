<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

use Util\FileSystem\FileSystemReader;
use Util\FileSystem\FileSystemWriter;
use Util\OsInformationFetcher;
use Util\Shell\ShellExecutor;

/**
 * Stores ffmpeg configuration and generates a usable command.
 *
 * @package Output
 */
class FfmpegHelper
{
    /**
     * The path to the ffmpeg binary file
     *
     * @var String $binaryPath
     */
    private $binaryPath;

    /**
     * The file system reader
     *
     * @var FileSystemReader $fileSystemReader
     */
    private $fileSystemReader;

    /**
     * The file system writer
     *
     * @var FileSystemWriter $fileSystemWriter
     */
    private $fileSystemWriter;

    /**
     * The list of ffmpeg options
     *
     * @var String[] $options
     */
    private $options;

    /**
     * The os information fetcher
     *
     * @var OsInformationFetcher $osInformationFetcher
     */
    private $osInformationFetcher;

    /**
     * The shell executor
     *
     * @var ShellExecutor $shellExecutor
     */
    private $shellExecutor;


    /**
     * FfmpegHelper constructor.
     *
     * @throws \Exception The exception when the ffmpeg binary could not be found
     */
    public function __construct()
    {
        $this->fileSystemReader = new FileSystemReader();
        $this->fileSystemWriter = new FileSystemWriter();
        $this->options = array();
        $this->shellExecutor = new ShellExecutor();

        $this->osInformationFetcher = new OsInformationFetcher();
        $this->binaryPath = $this->findFFmpegBinary();
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
        $binaryPath = "";

        if ($this->osInformationFetcher->isWindows())
        { // If OS is Windows search the Tools directory for the ffmpeg.exe file
            $searchDirectory = __DIR__ . "/../../../../Tools";
            $binaryPath = $this->fileSystemReader->findFileRecursive($searchDirectory, "ffmpeg.exe");

            if ($binaryPath === null) throw new \Exception("The ffmpeg.exe file could not be found in \"" . $searchDirectory . "\".");
        }
        elseif ($this->osInformationFetcher->isLinux())
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

        if ($this->osInformationFetcher->isWindows()) $command .= "\"";
        $command .= $this->binaryPath;
        if ($this->osInformationFetcher->isWindows()) $command .= "\"";

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
