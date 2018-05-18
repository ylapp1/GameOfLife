<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\Shell;

/**
 * Reads inputs from the shell.
 */
class ShellInputReader
{
    // Attributes

    /**
     * Stores the last line that was added to the history
     *
     * @var String $lastHistoryLine
     */
    private $lastHistoryLine;

    /**
     * The shell information fetcher
     *
     * @var
     */
    private $shellInformationFetcher;


    // Magic Methods

    /**
     * ShellInputReader constructor.
     */
    public function __construct()
    {
        $this->lastHistoryLine = "";
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
     * Adds an input line to the history in order to be able to use ARROW UP and ARROW DOWN keys
     * to navigate to previously used commands.
     *
     * @param String $_line The line that was read
     */
    private function addLineToHistory(String $_line)
    {
        if ($_line != "" &&
            $_line != $this->lastHistoryLine ||
            stristr(PHP_OS, "win"))
        {
            /*
             * For windows the readline_add_history method must be called after every readline call,
             * otherwise the history will be broken
             */
            readline_add_history($_line);
            $this->lastHistoryLine = $_line;
        }
    }
}
