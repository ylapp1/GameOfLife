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
use GameOfLife\Field;
use Output\BoardEditorOutput;
use Ulrichsg\Getopt;
use Utils\Shell\ShellInformationFetcher;
use Utils\Shell\ShellInputReader;
use Utils\Shell\ShellOutputHelper;

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
     * The shell information fetcher
     *
     * @var ShellInformationFetcher $shellInformationFetcher
     */
    private $shellInformationFetcher;

    /**
     * The shell input reader
     *
     * @var ShellInputReader $shellInputReader
     */
    private $shellInputReader;

    /**
     * The shell output helper
     *
     * @var ShellOutputHelper $shellOutputHelper
     */
    private $shellOutputHelper;

    /**
     * Template directory which will be used in this board editor session
     *
     * @var String $templateDirectory
     */
    private $templateDirectory;

    /**
     * The coordinates of the top left and bottom right corner of the currently selected area
     * The array is in the format array("A" => array("x", "y"))
     *
     * @var array $selection
     */
    private $selectionCoordinates;

    /**
     * The cached copied fields.
     *
     * @var Field[] $copiedFields
     */
    private $copiedFields;

    /**
     * The field that is currently highlighted
     * Array in the format array("x" => x, "y" => y)
     *
     * @var array $highLightField
     */
    private $highLightField;


    /**
     * BoardEditor constructor.
     *
     * @param String $_templateDirectory The directory to which templates will be saved
     * @param Board $_board The board which will be edited
     *
     * @throws \Exception
     */
    public function __construct(String $_templateDirectory, Board $_board = null)
    {
        if (isset($_board)) $this->board = $_board;

        $this->templateDirectory = $_templateDirectory;

        try
        {
            $this->optionHandler = new BoardEditorOptionHandler($this);
        }
        catch (\Exception $_exception)
        {
            throw new \Exception("Error while initializing the board editor option handler: " . $_exception->getMessage());
        }
        $this->output = new BoardEditorOutput();
        $this->selectionCoordinates = array();
        $this->copiedFields = array();

        $this->shellInformationFetcher = new ShellInformationFetcher();
        $this->shellInputReader = new ShellInputReader();
        $this->shellOutputHelper = new ShellOutputHelper();

        $this->highLightField = array();
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
     * Sets the output which prints the board.
     *
     * @param BoardEditorOutput $_output Output which prints the board
     */
    public function setOutput(BoardEditorOutput $_output)
    {
        $this->output = $_output;
    }

    /**
     * Returns the selection coordinates.
     *
     * @return array The selection coordinates
     */
    public function selectionCoordinates(): array
    {
        return $this->selectionCoordinates;
    }

    /**
     * Sets the selection coordinates.
     *
     * @param array $_selectionCoordinates The selection coordinates
     */
    public function setSelectionCoordinates(array $_selectionCoordinates)
    {
        $this->selectionCoordinates = $_selectionCoordinates;
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
     * Sets the template directory.
     *
     * @param String $_templateDirectory Template directory
     */
    public function setTemplateDirectory(String $_templateDirectory)
    {
        $this->templateDirectory = $_templateDirectory;
    }

    /**
     * Returns the cached copied fields.
     *
     * @return array The cached copied fields
     */
    public function copiedFields(): array
    {
        return $this->copiedFields;
    }

    /**
     * Sets the cached copied fields.
     *
     * @param array $_copiedFields The cached copied fields
     */
    public function setCopiedFields(array $_copiedFields)
    {
        $this->copiedFields = $_copiedFields;
    }

    /**
     * Returns the shell output helper.
     *
     * @return ShellOutputHelper
     */
    public function shellOutputHelper()
    {
        return $this->shellOutputHelper;
    }


    /**
     * Launches the board editor session.
     *
     * @throws \Exception The exception when the option "help" could not be parsed
     */
    public function launch()
    {
        $isInputFinished = false;
        $message = "Say \"options\" to see a list of available options.";
        $this->outputBoard($message);

        while (! $isInputFinished)
        {
            $line = $this->readInput("> ");

            try
            {
                $isInputFinished = $this->optionHandler->parseInput($line);
            }
            catch (\Exception $_exception)
            {
                $this->outputBoard("Error while parsing the option: " . $_exception->getMessage());
            }
        }
    }

    /**
     * Outputs the current board.
     *
     * @param String $_message The message that will be displayed below the board
     */
    public function outputBoard(String $_message = "")
    {
        $this->output->startOutput(new Getopt(), new Board(0, 0, 0, false));
        $numberOfNewLines = $this->shellInformationFetcher->getNumberOfShellLines() - $this->getNumberOfUsedLines();

        if ($this->highLightField != array())
        {
            $this->output->outputBoard($this->board, false, $this->highLightField["x"], $this->highLightField["y"]);
            $this->highLightField = array();
        }
        else $this->output->outputBoard($this->board, false, null, null, $this->selectionCoordinates);

        if ($numberOfNewLines < 0) $numberOfNewLines = 0;

        if ($_message) echo $_message;
        echo str_repeat("\n", $numberOfNewLines);
    }

    /**
     * Returns the number of lines that are used by the output.
     *
     * @return int The number of lines that are used by the output
     */
    private function getNumberOfUsedLines(): int
    {
        $numberOfUsedLines = 0;

        // Determine borders of highlight or selection
        $hasTopBorder = false;
        $hasBottomBorder = false;

        if ($this->highLightField != array())
        {
            if ($this->highLightField["y"] > 0) $hasTopBorder = true;
            if ($this->highLightField["y"] < $this->board->height() - 1) $hasBottomBorder = true;

            /*
             * 1x X-Coordinate number above board
             */
            $numberOfUsedLines += 1;
        }
        elseif ($this->selectionCoordinates != array())
        {
            if ($this->selectionCoordinates["A"]["y"] > 0) $hasTopBorder = true;
            if ($this->selectionCoordinates["B"]["y"] < $this->board->height() - 1) $hasBottomBorder = true;
        }

        $numberOfUsedLines += $hasTopBorder + $hasBottomBorder;

        /*
         * 4x Title
         * 2x Board border
         * 2x Board output margin bottom
         */
        $numberOfUsedLines += $this->board->height() + 8;

        return $numberOfUsedLines;
    }

    /**
     * Reads user input from a input source.
     *
     * @param String $_prompt The prompt
     *
     * @return String User input with removed "\n\r"
     */
    public function readInput(String $_prompt = ""): String
    {
        return $this->shellInputReader->readInput($_prompt);
    }

    /**
     * Reads an input coordinate.
     *
     * @param String $_coordinateAxisName The name of the coordinate axis ("X" or "Y")
     * @param String $_coordinateDescription The description of the coordinate
     * @param int $_minValue The minimum value of the coordinate
     * @param int $_maxValue The maximum value of the coordinate
     *
     * @return int The read coordinate
     *
     * @throws \Exception The exception when the input value is invalid
     */
    public function readCoordinate(String $_coordinateAxisName, String $_coordinateDescription, int $_minValue, int $_maxValue): int
    {
        $userInput = $this->readInput($_coordinateAxisName . "-Coordinate of the " . $_coordinateDescription . ": ");
        if (! is_numeric($userInput)) throw new \Exception("The input value is no number.");
        else
        {
            $coordinate = (int)$userInput;
            $this->checkCoordinate($coordinate, $_coordinateAxisName, $_minValue, $_maxValue);
        }

        return $coordinate;
    }

    /**
     * Checks whether a coordinate is in the defined range.
     *
     * @param int $_coordinate The name of the coordinate axis ("X" or "Y")
     * @param String $_coordinateAxisName The name of the
     * @param int $_minValue The min value
     * @param int $_maxValue The max value
     *
     * @throws \Exception The exception when the coordinate exceeds the range
     */
    public function checkCoordinate(int $_coordinate, String $_coordinateAxisName, int $_minValue, int $_maxValue)
    {
        if ($_coordinate < $_minValue)
        {
            throw new \Exception("The " . $_coordinateAxisName . "-Position may not be smaller than " . $_minValue . ".");
        }
        elseif ($_coordinate > $_maxValue)
        {
            throw new \Exception("The " . $_coordinateAxisName . "-Position may not be larger than " . $_maxValue . ".");
        }
    }

    /**
     * Selects a part of the board.
     *
     * @param int $_x1 The x1 position
     * @param int $_y1 The y1 position
     * @param int $_x2 The x2 position
     * @param int $_y2 The y2 position
     *
     * @throws \Exception The exception when one of the input coordinates is invalid
     */
    public function selectArea(int $_x1, int $_y1, int $_x2, int $_y2)
    {
        $pointACoordinate = array();
        $pointBCoordinate = array();

        // Get x coordinate of both points
        $this->checkCoordinate($_x1, "X", 0, $this->board->width());
        $this->checkCoordinate($_x2, "X", 0, $this->board->width());

        if ($_x2 >= $_x1)
        {
            $pointACoordinate["x"] = $_x1;
            $pointBCoordinate["x"] = $_x2;
        }
        else
        {
            $pointACoordinate["x"] = $_x2;
            $pointBCoordinate["x"] = $_x1;
        }

        // Get y coordinate of both points
        $this->checkCoordinate($_y1, "Y", 0, $this->board->height());
        $this->checkCoordinate($_y2, "Y", 0, $this->board->height());

        if ($_y2 >= $_y1)
        {
            $pointACoordinate["y"] = $_y1;
            $pointBCoordinate["y"] = $_y2;
        }
        else
        {
            $pointACoordinate["y"] = $_y2;
            $pointBCoordinate["y"] = $_y1;
        }

        $this->selectionCoordinates = array("A" => $pointACoordinate, "B" => $pointBCoordinate);
    }

    /**
     * Sets the high light field.
     *
     * @param int $_x The X-position of the highlighted field
     * @param int $_y The Y-Position of the highlighted field
     */
    public function setHighLightField(int $_x, int $_y)
    {
        $this->highLightField = array(
            "x" => $_x,
            "y" => $_y
        );
    }
}
