<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor;

use GameOfLife\Board;
use Output\BoardEditorOutput;
use Input\TemplateHandler\TemplateSaver;

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
     * @var Board
     */
    private $board;

    /**
     * Option list in the format "optionName" => BoardEditorOption $option
     *
     * @var BoardEditorOption[]
     */
    private $options;

    /**
     * Output that prints the board
     *
     * @var BoardEditorOutput
     */
    private $output;

    /**
     * The template saver that is used to save templates
     *
     * @var TemplateSaver
     */
    private $templateSaver;


    /**
     * BoardEditor constructor.
     *
     * @param String $_templateDirectory The directory to which templates will be saved
     * @param Board $_board The board which will be edited
     */
    public function __construct(String $_templateDirectory, Board $_board = null)
    {
        if (isset($_board)) $this->board = $_board;
        $this->output = new BoardEditorOutput();
        $this->templateSaver = new TemplateSaver($_templateDirectory);
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
     * Returns the option list.
     *
     * @return BoardEditorOption[] Option list
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * Sets the option list.
     *
     * @param array $_options New option list
     */
    public function setOptions(array $_options)
    {
        $this->options = $_options;
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
     * Returns the template saver.
     *
     * @return TemplateSaver The template saver
     */
    public function templateSaver(): TemplateSaver
    {
        return $this->templateSaver;
    }

    /**
     * Sets the template saver.
     *
     * @param TemplateSaver $_templateSaver New template saver
     */
    public function setTemplateSaver(TemplateSaver $_templateSaver)
    {
        $this->templateSaver = $_templateSaver;
    }


    /**
     * Calls an option from the option list.
     *
     * @param String $_optionName The option name
     * @param array $_arguments The arguments for the option
     *
     * @return bool True: Board Editor session is finished
     *              False: Board Editor session continues
     */
    private function callOption(String $_optionName, array $_arguments = null): bool
    {
        $callback = $this->options[$_optionName]->callback();

        $argument = null;
        if (count($_arguments) != 0) $argument = $_arguments[0];

        $sessionFinished = $this->options[$_optionName]->$callback($argument);

        return $sessionFinished;
    }

    /**
     * Launches the board editor.
     */
    public function launch()
    {
        $this->options = $this->loadOptions();
        $this->callOption("help");
        $this->output->outputBoard($this->board);

        $isInputFinished = false;
        while (! $isInputFinished)
        {
            echo "> ";

            $line = $this->readInput("php://stdin");
            $result = $this->isOption($line);

            if ($result !== false)
            {
                $isInputFinished = $this->callOption($result[0], $result[1]);
            }
            elseif (stristr($line, ",")) $this->setField($this->board, $line);
            else echo "Error: Invalid option or invalid coordinates format\n";
        }
    }

    /**
     * Loads all options from the options folder.
     *
     * @return BoardEditorOption[] array in the format ("optionName" => "optionObject")
     */
    private function loadOptions(): array
    {
        $options = array();

        // Load each option from the options folder
        $classes = glob(__DIR__ . "/Options/*Option.php");

        foreach ($classes as $class)
        {
            $className = basename($class, ".php");
            $classPath = "BoardEditor\\Options\\" . $className;

            $instance = new $classPath($this);
            if ($instance instanceof BoardEditorOption) $options[$instance->name()] = $instance;
        }

        return $options;
    }

    /**
     * Returns whether the input string is one of the registered options.
     *
     * @param String $_input Input string
     *
     * @return bool|array false or option name and arguments
     */
    private function isOption(String $_input)
    {
        $parts = explode(" ", $_input);
        $inputOption = array_shift($parts);

        if (count($parts) == 0) $parts = array();

        if (array_key_exists($inputOption, $this->options)) return array($inputOption, $parts);
        else return false;
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

    /**
     * Sets a field on the board and displays the updated board or displays an error in case of invalid coordinates.
     *
     * @param Board $_board The board
     * @param String $_inputCoordinates The user input coordinates in the format "x,y"
     */
    private function setField(Board $_board, String $_inputCoordinates)
    {
        $inputSplits = explode(",", $_inputCoordinates);

        if (count($inputSplits) == 2)
        {
            $inputX = $this->getInputCoordinate($inputSplits[0], 0, $_board->width() - 1);
            $inputY = $this->getInputCoordinate($inputSplits[1], 0, $_board->height() - 1);

            if ($inputX === false) echo "Error: Invalid value for x specified: Value exceeds field borders or is not set\n";
            elseif ($inputY === false) echo "Error: Invalid value for y specified: Value exceeds field borders or is not set\n";
            else
            {
                $currentCellState = $_board->getField($inputX, $inputY);
                $_board->setField($inputX, $inputY, !$currentCellState);
                $this->output->outputBoard($_board, $inputX, $inputY);
            }
        }
        else echo "Error: Please input exactly two values!\n";
    }

    /**
     * Converts user input to int and checks whether coordinate is inside field borders.
     *
     * @param String $_inputCoordinate User input string (a single coordinate)
     * @param int $_minValue The lowest value that the input coordinate may be
     * @param int $_maxValue The highest value that the input coordinate may be
     *
     * @return int|bool Input coordinate | Error
     */
    private function getInputCoordinate(String $_inputCoordinate, int $_minValue, int $_maxValue)
    {
        if ($_inputCoordinate == "") return false;

        // convert coordinate to integer
        $coordinate = (int)$_inputCoordinate;

        // Check whether field borders are exceeded
        if ($coordinate < $_minValue || $coordinate > $_maxValue) return false;
        else return $coordinate;
    }
}