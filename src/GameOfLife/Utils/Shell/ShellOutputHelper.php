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
    private $numberOfShellColumns;


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
        elseif(stristr(PHP_OS, "win")) echo $this->fakeClearScreenForWindows;
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
