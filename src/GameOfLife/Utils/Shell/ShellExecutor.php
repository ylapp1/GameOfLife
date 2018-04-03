<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\Shell;

/**
 * Handles executing of shell commands.
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
     * The output of the last executed command
     *
     * @var array $output
     */
    private $output;


    /**
     * ShellExecutor constructor.
     */
    public function __construct()
    {
        $this->output = array();
    }


    /**
     * Returns the output of the last executed command.
     *
     * @return String[] The output of the last executed command
     */
    public function output()
    {
        return $this->output;
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
        if (stristr(PHP_OS, "win")) return $this->outputHideRedirectWindows;
        elseif (stristr(PHP_OS, "linux")) return $this->outputHideRedirectLinux;
        else return $this->outputHideRedirectOther;
    }
}
