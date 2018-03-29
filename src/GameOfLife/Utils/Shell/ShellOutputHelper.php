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
     * ShellExecutor constructor.
     */
    public function __construct()
    {
        $this->fakeClearScreenForWindows = str_repeat("\n", 100);
    }


    /**
     * Clears the console screen.
     */
    public function clearScreen()
    {
        if (stristr(PHP_OS, "linux")) echo "\e[1;1H \n";
        elseif(stristr(PHP_OS, "win")) echo $this->fakeClearScreenForWindows;
    }
}
