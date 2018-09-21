<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Util\Shell;

use Util\OsInformationFetcher;

/**
 * Fetches information about the shell in which the script is executed.
 */
class ShellInformationFetcher
{
    // Attributes

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


    // Magic Methods

    /**
     * ShellInformationFetcher constructor.
     */
    public function __construct()
    {
        $this->osInformationFetcher = new OsInformationFetcher();
        $this->shellExecutor = new ShellExecutor();
    }


    // Class Methods

    /**
     * Returns the number of shell lines.
     *
     * @return int The number of shell lines
     */
    public function getNumberOfShellLines(): int
    {
        /*
         * 29 lines is the default number of lines in cmd
         * 50 lines is the default number of lines in PowerShell
         */
        $numberOfLines = 29;

        if ($this->osInformationFetcher->isWindows())
        {
            /*
             * Found no way to determine the number of lines in windows with pure php
             * That's why a custom .exe file is used to determine the number of lines
             */
            $this->shellExecutor->executeCommand(__DIR__ . "\ConsoleHelper.exe printNumberOfRows");
            $numberOfLines = (int)$this->shellExecutor->output()[0];
        }
        elseif ($this->osInformationFetcher->isLinux())
        {
            /*
             * Using "stty" instead of "tput" here because "tput" will display a wrong value
             * when redirecting stderr to stdout
             */
            $returnValue = $this->shellExecutor->executeCommand("stty size");
            if ($returnValue === 0)
            {
                $dimensionsString = $this->shellExecutor->output()[0];
                $dimensions = explode(" ", $dimensionsString);
                $numberOfLines = (int)$dimensions[0];
            }
        }

        return $numberOfLines;
    }

    /**
     * Returns the number of shell columns.
     *
     * @return int The number of shell columns
     */
    public function getNumberOfShellColumns(): int
    {
        // 120 is the default number of columns in cmd and PowerShell
        $numberOfColumns = 120;

        if ($this->osInformationFetcher->isWindows())
        {
            /*
             * The custom .exe file is used here instead of "mode con /status" because that command
             * will return a wrong number of columns in PowerShell when reducing the width of the window
             */
            $this->shellExecutor->executeCommand(__DIR__ . "\ConsoleHelper.exe printNumberOfColumns");
            $numberOfColumns = (int)$this->shellExecutor->output()[0];
        }
        elseif ($this->osInformationFetcher->isLinux())
        {
            /*
             * Using "stty" instead of "tput" here because "tput" will display a wrong value
             * when redirecting stderr to stdout
             */
            $returnValue = $this->shellExecutor->executeCommand("stty size");
            if ($returnValue === 0)
            {
                $dimensionsString = $this->shellExecutor->output()[0];
                $dimensions = explode(" ", $dimensionsString);
                $numberOfColumns = (int)$dimensions[1];
            }
        }

        return $numberOfColumns;
    }
}
