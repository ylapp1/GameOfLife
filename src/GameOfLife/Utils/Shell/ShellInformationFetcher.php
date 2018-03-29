<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\Shell;

/**
 * Fetches information about the shell in which the script is executed.
 */
class ShellInformationFetcher
{
    /**
     * The shell executor
     *
     * @var ShellExecutor $shellExecutor
     */
    private $shellExecutor;


    /**
     * ShellInformationFetcher constructor.
     */
    public function __construct()
    {
        $this->shellExecutor = new ShellExecutor();
    }


    /**
     * Returns the number of shell lines.
     *
     * @return int The number of shell ines
     */
    public function getNumberOfShellLines()
    {
        // 29 lines is the default number of lines for cmd
        // 50 lines is the default number of lines for PowerShell
        // Found no way to determine the number of lines in lines in windows
        $numberOfLines = 29;

        if (stristr(PHP_OS, "linux"))
        {
            $this->shellExecutor->executeCommand("tput lines");
            $numberOfLines = (int)$this->shellExecutor->output()[0];
        }

        return $numberOfLines;
    }

    /**
     * Returns the number of shell columns.
     *
     * @return int The number of shell columns
     */
    public function getNumberOfShellColumns()
    {
        // 120 is the default number of columns for cmd and PowerShell
        $numberOfColumns = 120;

        if (stristr(PHP_OS, "win"))
        {
            // This command will return a wrong number of columns in PowerShell when reducing the width of the window
            $this->shellExecutor->executeCommand("mode con /status");

            $matches = array();
            preg_match("/\d{1,3}/", $this->shellExecutor->output()[4], $matches);
            $numberOfColumns = (int)$matches[0];
        }
        elseif (stristr(PHP_OS, "linux"))
        {
            $this->shellExecutor->executeCommand("tput cols");
            $numberOfColumns = (int)$this->shellExecutor->output()[0];
        }

        return $numberOfColumns;
    }
}
