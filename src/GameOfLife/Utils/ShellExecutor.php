<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils;

/**
 * Executes shell commands.
 */
class ShellExecutor
{
    /**
     * Stores the path to which you can redirect standard output without saving it in Linux
     *
     * @var String $outputHideRedirectLinux
     */
    private $outputHideRedirectLinux = "/dev/null";

    /**
     * Stores the path to which you can redirect standard output without saving it in Windows
     *
     * @var String $outputHideRedirectWindows
     */
    private $outputHideRedirectWindows = "NUL";

    /**
     * Stores the path for output redirects for other operating systems
     *
     * @var String
     */
    private $outputHideRedirectOther = "output.txt";

    /**
     * The os name
     *
     * @var String $osName
     */
    private $osName;

    /**
     * The output of the last executed command
     *
     * @var array $output
     */
    private $output;

    /**
     * The fake clear screen for windows (100 new lines)
     *
     * @var String $fakeClearScreenForWindows
     */
    private $fakeClearScreenForWindows;


    /**
     * ShellExecutor constructor.
     *
     * @param String $_osName The os name
     */
    public function __construct(String $_osName)
    {
        $this->fakeClearScreenForWindows = str_repeat("\n", 100);
        $this->osName = $_osName;
        $this->output = array();
    }


    /**
     * Returns the os name.
     *
     * @return String The os name
     */
    public function osName(): String
    {
        return $this->osName;
    }

    /**
     * Sets the os name.
     *
     * @param String $_osName The os name
     */
    public function setOsname(String $_osName)
    {
        $this->osName = $_osName;
    }


    /**
     * Executes a command and optionally hides the output from the user.
     *
     * @param String $_command The command
     * @param bool $_hideOutput Indicates whether the output will be hidden or not
     *
     * @return int The return code of the command
     */
    public function executeCommand(String $_command, Bool $_hideOutput = false)
    {
        $returnValue = 0;
        $this->output = array();

        if ($_hideOutput) $_command .= " 2>" . $this->getOutputHideRedirect();
        exec($_command, $this->output, $returnValue);

        return $returnValue;
    }

    /**
     * Returns the path to which the output will be redirected in case of hiding it.
     *
     * @return String The path to which the output will be redirected
     */
    private function getOutputHideRedirect()
    {
        if (stristr($this->osName, "win")) return $this->outputHideRedirectWindows;
        elseif (stristr($this->osName, "linux")) return $this->outputHideRedirectLinux;
        else return $this->outputHideRedirectOther;
    }

    public function clearScreen()
    {
        if (stristr($this->osName, "linux")) system("clear");
        elseif(stristr($this->osName, "win")) echo $this->fakeClearScreenForWindows;
    }

    /**
     * Returns the number of shell lines.
     */
    public function getNumberOfShellLines()
    {
        // 29 lines is the default height in cmd
        // 50 lines is the default height in powershell
        // Found no way to determine the height in lines in windows
        $height = 29;

        if (stristr($this->osName, "linux"))
        {
            $this->executeCommand("tput lines");
            $height = (int)$this->output[0];
        }

        return $height;
    }

    /**
     * Returns the number of shell columns.
     *
     * @return int The number of shell columns
     */
    public function getNumberOfShellColumns()
    {
        // 120 is the default number of columns for cmd and powershell
        $numberOfColumns = 120;

        if (stristr($this->osName, "win"))
        {
            $this->executeCommand("mode con /status");

            $matches = array();
            preg_match("/\d{1,3}/", $this->output[4], $matches);
            $numberOfColumns = (int)$matches[0];
        }
        elseif (stristr($this->osName, "linux"))
        {
            $this->executeCommand("tput cols");
            $numberOfColumns = (int)$this->output[0];
        }

        return $numberOfColumns;
    }
}
