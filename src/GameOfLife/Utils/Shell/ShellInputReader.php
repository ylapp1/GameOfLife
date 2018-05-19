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
 * Reads inputs from the shell.
 */
class ShellInputReader
{
    // Attributes

    /**
     * Stores the last input line that was added to the history
     *
     * @var String $lastHistoryLine
     */
    private $lastHistoryLine;

    /**
     * The os information fetcher
     *
     * @var OsInformationFetcher $osInformationFetcher
     */
    private $osInformationFetcher;


    // Magic Methods

    /**
     * ShellInputReader constructor.
     */
    public function __construct()
    {
        $this->lastHistoryLine = "";
        $this->osInformationFetcher = new OsInformationFetcher();
    }


    // Class Methods

    /**
     * Reads input from the shell.
     *
     * @param String $_prompt The prompt that will be displayed in front of the input area
     *
     * @return String The input line
     */
    public function readInput(String $_prompt): String
    {
        $inputLine = readline($_prompt);
        $inputLine = trim($inputLine);
        $this->addLineToHistory($inputLine);

        return $inputLine;
    }

    /**
     * Adds an input line to the history in order to be able to use the ARROW UP and
     * ARROW DOWN keys to navigate to previously used commands.
     *
     * @param String $_inputLine The input line
     */
    private function addLineToHistory(String $_inputLine)
    {
        if ($_inputLine && $_inputLine != $this->lastHistoryLine ||
            $this->osInformationFetcher->isWindows())
        {
            /*
             * In Windows the readline_add_history method must be called after every readline call,
             * otherwise the history will be broken
             */
            readline_add_history($_inputLine);
            $this->lastHistoryLine = $_inputLine;
        }
    }
}
