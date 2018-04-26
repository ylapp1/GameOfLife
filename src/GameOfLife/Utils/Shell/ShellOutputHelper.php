<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\Shell;

/**
 * Contains useful methods for shell outputs.
 */
class ShellOutputHelper
{
    /**
     * The fake clear screen for windows (100 new lines)
     * This means that the functions using clearScreen must also implement the logic to add new lines below the output
     * to move it back up to the top of the console
     *
     * @var String $fakeClearScreenForWindows
     */
    private $fakeClearScreenForWindows;

    /**
     * The number of shell columns
     *
     * @var int $numberOfShellColumns
     */
    protected $numberOfShellColumns;


    /**
     * ShellExecutor constructor.
     */
    public function __construct()
    {
        $shellInformationFetcher = new ShellInformationFetcher();

        $this->fakeClearScreenForWindows = str_repeat("\n", $shellInformationFetcher->getNumberOfShellLines());
        $this->numberOfShellColumns = $shellInformationFetcher->getNumberOfShellColumns();

        unset($shellInformationFetcher);
    }


    /**
     * Clears the console screen.
     */
    public function clearScreen()
    {
        if (stristr(PHP_OS, "linux")) echo "\e[1;1H \n";
        elseif(stristr(PHP_OS, "win"))
        {
            /*
             * A pure php way to clear the screen would be to add 10000 new lines at the beginning of
             * the simulation in order to move the scroll bar in cmd to the bottom.
             * Then with each clear screen call one screen would be filled with empty lines in order to move the previous
             * board away from the visible output.
             *
             * This variant however behaves exactly like the Linux version and does not fill the output buffer with
             * unnecessary lines.
             */
            $this->shellExecutor->executeCommand(__DIR__ . "\ConsoleHelper.exe setCursor 0 2");
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

        return str_repeat(" ", $paddingLeft) . $_outputString;
    }
}
