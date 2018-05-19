<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\Shell;

use Utils\OsInformationFetcher;

/**
 * Provides methods to format shell outputs.
 */
class ShellOutputHelper
{
    // Attributes

    /**
     * The cached number of shell columns
     *
     * @var int $numberOfShellColumns
     */
    private $numberOfShellColumns;

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
     * ShellOutputHelper constructor.
     */
    public function __construct()
    {
        $shellInformationFetcher = new ShellInformationFetcher();
        $this->numberOfShellColumns = $shellInformationFetcher->getNumberOfShellColumns();
        unset($shellInformationFetcher);

        $this->osInformationFetcher = new OsInformationFetcher();
        $this->shellExecutor = new ShellExecutor();
    }


    // Class Methods

    /**
     * Clears the console screen.
     * This is accomplished by moving the cursor back to the top left corner of the shell window.
     */
    public function clearScreen()
    {
        if ($this->osInformationFetcher->isLinux()) echo "\e[1;0H";
        elseif($this->osInformationFetcher->isWindows())
        {
            /*
             * A pure php way to clear the screen would be to add 10000 new lines at the beginning of
             * the simulation in order to move the scroll bar in cmd to the bottom.
             * Then with each clear screen call one screen would be filled with empty lines in order to
             * move the previous board away from the visible output.
             *
             * This variant however behaves exactly like the Linux version and does not fill the output buffer with
             * unnecessary lines.
             */
            $this->shellExecutor->executeCommand(__DIR__ . "\ConsoleHelper.exe setCursor 0 1");
        }
    }

    /**
     * Returns a centered output string relative to the number of shell columns.
     *
     * @param String $_outputString The output string
     *
     * @return String The centered output string
     */
    public function getCenteredOutputString(String $_outputString): String
    {
        $stringLength = mb_strlen($_outputString);
        $paddingLeft = ceil(($this->numberOfShellColumns - $stringLength) / 2) + 1;

        $outputString = $_outputString;
        if ($paddingLeft > 0) $outputString = str_repeat(" ", $paddingLeft) . $outputString;

        return $outputString;
    }
}
