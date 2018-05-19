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
 * Handles executing of shell commands.
 */
class ShellExecutor
{
    // Attributes

    /**
     * The path to which you can redirect standard output without saving it in Linux
     *
     * @var String $outputHideRedirectLinux
     */
    private $outputHideRedirectLinux = "/dev/null";

    /**
     * The path to which you can redirect standard output without saving it in Windows
     *
     * @var String $outputHideRedirectWindows
     */
    private $outputHideRedirectWindows = "NUL";

    /**
     * The path for output redirects for other operating systems
     *
     * @var String $outputHideRedirectOther
     */
    private $outputHideRedirectOther = "output.txt";

    /**
     * The output of the last executed command
     *
     * @var String[] $output
     */
    private $output;

    /**
     * The os information fetcher
     *
     * @var OsInformationFetcher $osInformationFetcher
     */
    private $osInformationFetcher;


    // Magic Methods

    /**
     * ShellExecutor constructor.
     */
    public function __construct()
    {
        $this->output = array();
        $this->osInformationFetcher = new OsInformationFetcher();
    }


    // Getters and Setters

    /**
     * Returns the output of the last executed command.
     *
     * @return String[] The output of the last executed command
     */
    public function output(): array
    {
        return $this->output;
    }


    // Class Methods

    /**
     * Executes a shell command and optionally hides the output from the user.
     *
     * @param String $_command The command
     * @param Bool $_hideOutput If set to true the output will not be displayed to the user
     *
     * @return int The return code of the executed command
     */
    public function executeCommand(String $_command, Bool $_hideOutput = false): int
    {
        $returnValue = 0;
        $this->output = array();

        $command = $_command;
        if ($_hideOutput)
        { // Redirect standard output
            $command .= " >" . $this->getOutputHideRedirect();
        }

        // Redirect standard error to standard output
        $command .= " 2>&1";

        exec($command, $this->output, $returnValue);

        return $returnValue;
    }

    /**
     * Returns the path to which the output will be redirected in order to hide it.
     *
     * @return String The path to which the output will be redirected
     */
    private function getOutputHideRedirect(): String
    {
        if ($this->osInformationFetcher->isWindows()) return $this->outputHideRedirectWindows;
        elseif ($this->osInformationFetcher->isLinux()) return $this->outputHideRedirectLinux;
        else return $this->outputHideRedirectOther;
    }
}
