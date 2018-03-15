<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor;

use BoardEditor\OptionHandler\BoardEditorOptionHandler;
use GameOfLife\Board;
use Output\BoardEditorOutput;

/**
 * Lets the user edit a board by using options or toggling cells.
 *
 * Prompts the user to input which cells shall be set/unset
 * Numbers must be entered in the format "x,y"
 *
 * call launch() to launch the board editor
 */
class BoardEditor
{
    /**
     * The board that is edited
     *
     * @var Board $board
     */
    private $board;

    /**
     * Option Handler which loads and parses options.
     *
     * @var BoardEditorOptionHandler $optionHandler
     */
    private $optionHandler;

    /**
     * Output that prints the board
     *
     * @var BoardEditorOutput $output
     */
    private $output;

    /**
     * Template directory which will be used in this board editor session
     *
     * @var String $templateDirectory
     */
    private $templateDirectory;


    /**
     * BoardEditor constructor.
     *
     * @param String $_templateDirectory The directory to which templates will be saved
     * @param Board $_board The board which will be edited
     */
    public function __construct(String $_templateDirectory, Board $_board = null)
    {
        if (isset($_board)) $this->board = $_board;

        $this->templateDirectory = $_templateDirectory;
        $this->optionHandler = new BoardEditorOptionHandler($this);
        $this->output = new BoardEditorOutput();
    }


    /**
     * Returns the board.
     *
     * @return Board The board
     */
    public function board(): Board
    {
        return $this->board;
    }

    /**
     * Sets the board.
     *
     * @param Board $_board New board
     */
    public function setBoard(Board $_board)
    {
        $this->board = $_board;
    }

    /**
     * Returns the option handler.
     *
     * @return BoardEditorOptionHandler Option handler
     */
    public function optionHandler(): BoardEditorOptionHandler
    {
        return $this->optionHandler;
    }

    /**
     * Sets the option handler.
     *
     * @param BoardEditorOptionHandler $_optionHandler Option handler
     */
    public function setOptionHandler(BoardEditorOptionhandler $_optionHandler)
    {
        $this->optionHandler = $_optionHandler;
    }

    /**
     * Returns the output which prints the board.
     *
     * @return BoardEditorOutput Output which prints the board
     */
    public function output(): BoardEditorOutput
    {
        return $this->output;
    }

    /**
     * Sets the output which prints the board
     *
     * @param BoardEditorOutput $_output Output which prints the board
     */
    public function setOutput(BoardEditorOutput $_output)
    {
        $this->output = $_output;
    }

    /**
     * Returns the template directory.
     *
     * @return String Template directory
     */
    public function templateDirectory(): String
    {
        return $this->templateDirectory;
    }

    /**
     * Sets the template directory
     *
     * @param String $_templateDirectory Template directory
     */
    public function setTemplateDirectory(String $_templateDirectory)
    {
        $this->templateDirectory = $_templateDirectory;
    }


    /**
     * Launches the board editor session.
     */
    public function launch()
    {
        $this->optionHandler->parseInput("help");
        $this->output->outputBoard($this->board);

        $isInputFinished = false;
        while (! $isInputFinished)
        {
            echo "> ";

            $line = $this->readInput("php://stdin");
            $isInputFinished = $this->optionHandler->parseInput($line);
        }
    }

    /**
     * Reads user input from a input source.
     *
     * @param String $_source Input source (e.g. php://stdin)
     *
     * @return String User input with removed "\n\r"
     */
    public function readInput(String $_source): String
    {
        $fileOpen = fopen($_source,'r');
        $inputLine = fgets($fileOpen,1024);
        fclose($fileOpen);

        return rtrim($inputLine, "\n\r");
    }
}
