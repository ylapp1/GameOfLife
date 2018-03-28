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
     * ShellExecutor constructor.
     *
     * @param String $_osName The os name
     */
    public function __construct(String $_osName)
    {
        $this->osName = $_osName;
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
        $output = array();
        $returnValue = 0;

        if ($_hideOutput) $_command .= " 2>" . $this->getOutputHideRedirect();
        exec($_command, $output, $returnValue);

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
        elseif(stristr($this->osName, "win"))
        {
            // For some reason adding more lines runs smoother than adding less lines
            // The disadvantage is that you have to add lines below the board in order to move it back up to the top
            echo str_repeat("\n", 1000);
        }

        /*
         * It's not possible to clear the screen in cmd. (Ideas were using "cls" or moving the cursor position up)
         */
    }
}
