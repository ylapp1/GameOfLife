<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Prompts the user to input which cells shall be set/unset.
 *
 * Asks for coordinates which must be entered in the format "x,y"
 * The user can use the following commands while editing:
 *   - exit       Exit the simulation without calculating anything
 *   - reset      Reset the board to an empty board
 *   - setHeight  Change the height of the board
 *   - setWidth   Change the width of the board
 *   - save       Save the created board as a template for later usage
 *   - start      Start the simulation with the created board
 */
class UserInput extends BaseInput
{
    private $templateDirectory = __DIR__ . "/../../../Input/Templates/";


    /**
     * Returns the template directory in which UserInput will create the folder Custom where it saves custom templates.
     *
     * @return string   Template directory
     */
    public function templateDirectory(): string
    {
        return $this->templateDirectory;
    }

    /**
     * Sets the template directory.
     *
     * @param string $_templateDirectory    Template directory
     */
    public function setTemplateDirectory(string $_templateDirectory)
    {
        $this->templateDirectory = $_templateDirectory;
    }

    /**
     * Adds UserInputs specific options to the option list.
     *
     * @param Getopt $_options  Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "edit", Getopt::NO_ARGUMENT, "Edit a template"))
        );
    }

    /**
     * Catches User Inputs from input source.
     *
     * @param string $_inputSource  Input source (e.g. php://stdin)
     * @return string   User Input
     */
    public function catchUserInput(string $_inputSource): string
    {
        $fileOpen = fopen($_inputSource,'r');
        $inputLine = fgets($fileOpen,1024);
        fclose($fileOpen);

        return rtrim($inputLine, "\n\r");
    }

    /**
     * Catches input from keyboard to create an own generation.
     *
     * Put Numbers in like 5,5 to set to true
     *
     * @param \GameOfLife\Board $_board     The board which will be filled
     * @param Getopt $_options              Options (edit, template)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("edit") !== null)
        {
            $fileInput = new FileInput();
            $fileInput->setTemplateDirectory($this->templateDirectory());
            $fileInput->fillBoard($_board, $_options);
            $this->printBoardEditor($_board);
        }

        echo "Set the coordinates for the living cells as below:\n";
        echo "<X-Coordinate>,<Y-Coordinate>\n";
        echo "Enter the coordinates of a set field to unset it.\n";
        echo "The game starts when you type \"start\" in a new line and press <"."Enter>\n";
        echo "You can save your board configuration before starting the simulation by typing \"save\"\n";
        echo "Type \"options\" to see a list of all valid options\n";
        echo "Let's Go:\n";

        $isInputFinished = false;
        while (! $isInputFinished)
        {
            $input = $this->catchUserInput('php://stdin');
            $isInputFinished = $this->processInput($input, $_board);
        }
    }

    /**
     * Converts user input to int and checks whether coordinate is inside field borders.
     *
     * @param string $_inputCoordinate  User input string (a single coordinate)
     * @param int $_minValue            The lowest value that the input coordinate may be
     * @param int $_maxValue            The highest value that the input coordinate may be
     *
     * @return int|bool   Input coordinate | Error
     */
    public function getInputCoordinate(string $_inputCoordinate, int $_minValue, int $_maxValue)
    {
        if ($_inputCoordinate == "") return false;

        // convert coordinate to integer
        $coordinate = (int)$_inputCoordinate;

        // Check whether field borders are exceeded
        if ($coordinate < $_minValue || $coordinate > $_maxValue) return false;
        else return $coordinate;
    }

    /**
     * Processes the user input.
     *
     * @param String $_input    User input
     * @param Board $_board     Game Board
     *
     * @return bool isInputFinished     Indicates whether the simulation shall be started or not
     */
    public function processInput(String $_input, Board $_board): bool
    {
        if (stristr($_input, "exit"))
        {
            $_board->resetCurrentBoard();
            return true;
        }
        elseif (stristr($_input, "help"))
        {
            echo "Set the coordinates for the living cells as below:\n";
            echo "<X-Coordinate>,<Y-Coordinate>\n";
            echo "Enter the coordinates of a set field to unset it.\n";

            return false;
        }
        elseif (stristr($_input, "options"))
        {
            echo "\n\nOptions: ";
            echo "\n - exit:      Exit the application";
            echo "\n - help:      Display help";
            echo "\n - options:   Show available options";
            echo "\n - setHeight: Change the board height";
            echo "\n - setWidth:  Change the board width";
            echo "\n - save:      Save the current board to a custom template";
            echo "\n - start:     Star the simulation\n\n";

            return false;
        }
        elseif (stristr($_input, "reset"))
        {
            $_board->resetCurrentBoard();
            $this->printBoardEditor($_board);
            return false;
        }
        elseif (stristr($_input, "setHeight") || stristr($_input, "setWidth"))
        {
            $parts = explode(" ", $_input);
            $dimension = $parts[0];
            $value = (int)$parts[1];

            if ($value < 1) echo "Error, the board " . $dimension . " may not be less than 1";
            else
            {
                // copy the current board
                $board = $_board->currentBoard();
                $boardWidth = $_board->width();
                $boardHeight = $_board->height();

                // update board dimensions
                if ($dimension == "setWidth") $_board->setWidth($value);
                elseif ($dimension == "setHeight") $_board->setHeight($value);

                // reset board to an empty board with new dimensions
                $_board->resetCurrentBoard();

                // paste current board
                for ($y = 0; $y < $boardHeight; $y++)
                {
                    for ($x = 0; $x < $boardWidth; $x++)
                    {
                        if (isset($board[$y][$x])) $_board->setField($x, $y, true);
                    }
                }
            }

            $this->printBoardEditor($_board);
            return false;
        }
        elseif (stristr($_input, "start")) return true;
        elseif (stristr($_input, "save"))
        {
            $config = explode(" ", $_input);

            if (count($config) != 2) echo "Error: Invalid template name!\n";
            else $this->saveCustomTemplate($config[1], $_board);

            return false;
        }
        elseif (stristr($_input, ","))
        {
            $this->setField($_board, $_input);
            return false;
        }
        else
        {
            echo "Error: Input the coordinates in this format: <x" . ">,<y" . ">\n";
            return false;
        }
    }

    /**
     * Print the board to the console and highlights the cell at ($_curX | $_curY) if both values are set.
     *
     * @param Board $_board     Current board
     * @param Integer $_curX    X-Coordinate of the cell that shall be highlighted
     * @param Integer $_curY    Y-Coordinate of the cell that shall be highlighted
     */
    public function printBoardEditor(Board $_board, $_curX = null, $_curY = null)
    {
        $bonusDashes = 0;

        if (isset($_curX) && isset($_curY))
        {
            $isHighLight = true;

            if ($_curX == 0) $bonusDashes = 1;
            else $bonusDashes = 2;
        }
        else $isHighLight = false;

        // Output last set cell x-coordinate
        if ($isHighLight) echo "\n " . str_pad("", $_curX, " ") . $_curX;

        // print upper border
        echo "\n " . str_pad("", $_board->width() + $bonusDashes, "-");

        // print board
        for ($y = 0; $y < $_board->height(); $y++)
        {
            echo "\n|";

            // Output lines above and below the last set cell
            if ($isHighLight && $y != 0)
            {
                if ($y == $_curY || $y == $_curY + 1)
                {
                    echo str_pad("", $_board->width() + $bonusDashes, "-") . "|\n|";
                }
            }

            // Output cells
            for ($x = 0; $x < $_board->width(); $x++)
            {
                // Output lines left and right from the last set cel
                if ($isHighLight && $x != 0)
                {
                    if ($x == $_curX || $x == $_curX + 1) echo "|";
                }

                // Output the cells
                if ($_board->getField($x, $y))
                {
                    if (($x == $_curX && $y == $_curY) && $isHighLight) echo "X";
                    else echo "o";
                }
                else echo " ";
            }

            echo "|";

            // Output last set cell y-coordinate
            if ($y == $_curY) echo $_curY;
        }

        // Output bottom border
        echo "\n " . str_pad("", $_board->width() + $bonusDashes, "-") . "\n";
    }

    /**
     * Saves current board to a custom template file.
     *
     * @param string $_templateName    User input in the format "<save> <templateName>"
     * @param Board $_board            Current board
     */
    public function saveCustomTemplate(String $_templateName, Board $_board)
    {
        $fileSystemHandler = new FileSystemHandler();
        $fileSystemHandler->createDirectory($this->templateDirectory . "/Custom");
        $fileName = $_templateName . ".txt";

        $error = $fileSystemHandler->writeFile($this->templateDirectory . "/Custom", $fileName, $_board);

        if ($error !== FileSystemHandler::NO_ERROR)
        {
            echo "Warning: A template with that name already exists. Overwrite the old file? (Y|N)";
            $input = $this->catchUserInput('php://stdin');
            if (strtolower($input) == "y" or strtolower($input) == "yes")
            {
                $fileSystemHandler->writeFile($this->templateDirectory . "/Custom", $fileName, $_board, true);
                echo "Template successfully replaced!\n\n";
            }
            else echo "Saving aborted.\n\n";
        }
        else echo "Template successfully saved!\n\n";

        echo 'You can set/unset more cells or start the simulation by typing "start"' . "\n\n";
    }

    /**
     * Sets a field on the board and displays the updated board or displays an error in case of invalid coordinates.
     *
     * @param Board $_board                 The board
     * @param String $_inputCoordinates     The user input coordinates in the format "x,y"
     */
    public function setField(Board $_board, String $_inputCoordinates)
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
                $this->printBoardEditor($_board, $inputX, $inputY);
            }
        }
        else echo "Error: Please input exactly two values!\n";
    }
}