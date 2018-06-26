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
 * Provides methods to format shell outputs and to move the console cursor.
 */
class ShellOutputHelper
{
    // Attributes

    /**
     * The cached number of shell columns
     *
     * @var int $cachedNumberOfShellColumns
     */
    private $cachedNumberOfShellColumns;

    /**
     * The cached number of shell lines
     *
     * @var int $cachedNumberOfShellLines
     */
    private $cachedNumberOfShellLines;

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
     * The shell information fetcher
     *
     * @var ShellInformationFetcher $shellInformationFetcher
     */
    private $shellInformationFetcher;


    // Magic Methods

    /**
     * ShellOutputHelper constructor.
     */
    public function __construct()
    {
        $this->shellInformationFetcher = new ShellInformationFetcher();
        $this->cachedNumberOfShellColumns = $this->shellInformationFetcher->getNumberOfShellColumns();
        $this->cachedNumberOfShellLines = $this->shellInformationFetcher->getNumberOfShellLines();

        $this->osInformationFetcher = new OsInformationFetcher();
        $this->shellExecutor = new ShellExecutor();
    }


    // Class Methods

    // Cursor movement

    /**
     * Sets the cursor to a specific position relative to the current window dimensions.
     *
     * @param int $_x The new X-Position of the cursor
     * @param int $_y The new Y-Position of the cursor
     */
    private function setCursor(int $_x, int $_y)
    {
        if ($this->osInformationFetcher->isLinux()) echo "\e[" . $_y . ";" . $_x . "H";
        elseif ($this->osInformationFetcher->isWindows())
        {
            $this->shellExecutor->executeCommand(__DIR__ . "\\ConsoleHelper.exe setCursor " . $_x . " " . $_y);
        }
    }

    /**
     * Moves the cursor back to the top left corner of the shell window.
     */
    public function moveCursorToTopLeftCorner()
    {
        $this->setCursor(0, 1);
    }

    /**
     * Moves the cursor to the bottom left corner of the shell window.
     */
    public function moveCursorToBottomLeftCorner()
    {
        $bottomLineNumber = $this->cachedNumberOfShellLines;
        if ($this->osInformationFetcher->isWindows()) $bottomLineNumber -= 1;

        $this->setCursor(0, $bottomLineNumber);
    }

    /**
     * Clears the console screen.
     * This is achieved by filling the current window with empty lines and moving the cursor back to the top left corner.
     */
    public function clearScreen()
    {
        echo str_repeat("\n", $this->cachedNumberOfShellLines);
        $this->moveCursorToTopLeftCorner();

        /*
         * A pure php way to clear the screen in Windows would be to add 10000 new lines at the beginning of
         * the simulation in order to move the scroll bar in cmd to the bottom.
         * Then with each clear screen call one screen would be filled with empty lines in order to
         * move the previous board away from the visible output.
         *
         * This variant however behaves exactly like the Linux version and does not fill the output buffer with
         * unnecessary lines.
         */
    }


    // Centered output strings

    /**
     * Returns a centered output string relative to the number of shell columns.
     *
     * @param String $_outputString The output string
     *
     * @return String The centered output string
     */
    private function getCenteredOutputString(String $_outputString): String
    {
        $paddingLeft = floor(($this->cachedNumberOfShellColumns - mb_strlen($_outputString)) / 2) + 1;

        $outputString = $_outputString;
        if ($paddingLeft > 0) $outputString = str_repeat(" ", $paddingLeft) . $outputString;

        return $outputString;
    }

    /**
     * Prints a centered output string.
     *
     * @param String $_outputString The output string
     */
    public function printCenteredOutputString(String $_outputString)
    {
        $lines = explode("\n", $_outputString);

        foreach ($lines as $line)
        {
            if ($line) echo $this->getCenteredOutputString($line) . "\n";
            else echo "\n";
        }
    }
}
