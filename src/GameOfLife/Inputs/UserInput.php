<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 */

namespace Input;

use phpDocumentor\Reflection\File;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\FileSystemHandler;

/**
 * Class UserInput
 *
 * @package Input
 */
class UserInput extends BaseInput
{
    private $customTemplatesDirectory = __DIR__ . "/../../../Input/Templates/Custom/";

    /**
     * Adds UserInputs specific options to the option list
     *
     * @param Getopt $_options  Option list to which the objects options are added
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "edit", Getopt::NO_ARGUMENT, "Edit a template"))
        );
    }

    /**
     * Catches User Inputs
     *
     * @return string   User Input
     */
    public function catchUserInput()
    {
        $fileOpen = fopen('php://stdin','r') or die($php_errormsg);
        $inputLine = fgets($fileOpen,1024);
        fclose($fileOpen);

        return rtrim($inputLine, "\n\r");
    }

    /**
     * Saves current board to a custom template file
     *
     * @param string $_input    User input in the format "<save> <templateName>"
     * @param Board $_board     Current board
     * @return bool    error
     */
    public function saveCustomTemplate($_input, $_board)
    {
        $fileSystemHandler = new FileSystemHandler();
        $config = explode(" ", $_input);

        if (count($config) == 2)
        {
            $fileSystemHandler->createDirectory($this->customTemplatesDirectory);
            $fileName = $config[1] . ".txt";

            $success = $fileSystemHandler->writeFile($this->customTemplatesDirectory, $fileName, $_board);

            if ($success !== true)
            {
                echo "Warning: A template with that name already exists. Overwrite the old file? (Y|N)";
                $input = $this->catchUserInput();
                if (strtolower($input) == "y" or strtolower($input) == "yes")
                {
                    $fileSystemHandler->writeFile($this->customTemplatesDirectory, $fileName, $_board, true);
                    return false;
                }
                else return true;
            }
            else return false;
        }
        else return true;
    }


    /**
     * Catches input from keyboard to create an own generation
     * Put Numbers in like 5,5 to set to true
     * or -5,-5 to set to false
     *
     * @param \GameOfLife\Board $_board
     * @param Getopt $_options
     */
    public function fillBoard($_board, $_options)
    {
        if ($_options->getOption("edit"))
        {
            $fileInput = new FileInput();
            $fileInput->fillBoard($_board, $_options);
            $this->printBoardEditor($_board);
        }

        echo "Set the coordinates for the living cells as below:\n";
        echo "<X-Coordinate" . ">,<Y-Coordinate" . ">\n";
        echo "Enter the coordinates of a set field to unset it.\n";
        echo "The game starts when you type \"start\" in a new line and press <"."Enter>\n";
        echo "You can save your board configuration before starting the simulation by typing \"save\"\n";
        echo "Let's Go:\n";

        $inputX = null;
        $inputY = null;
        $inputFinished = false;

        while (! $inputFinished)
        {
            $input = $this->catchUserInput();

            if (stristr($input, "exit"))die();
            elseif (stristr($input, "start")) $inputFinished = true;
            elseif (stristr($input, "save"))
            {
                $error = $this->saveCustomTemplate($input, $_board);

                if ($error) echo "Error: No template name specified or saving aborted\n\n";
                else echo 'Template successfully saved!' . "\n\n" . 'You can set/unset more cells or start the simulation by typing "start"' . "\n\n";
            }
            else
            {
                if (stristr($input, ","))
                {
                    $inputSplits = explode(",", $input);

                    if (count($inputSplits) == 2)
                    {
                        $inputX = (int)$inputSplits[0];
                        $inputY = (int)$inputSplits[1];

                        if ($inputX > $_board->width() - 1 ||
                            $inputX < 0 ||
                            $inputY > $_board->height() - 1 ||
                            $inputY < 0)
                        {
                            echo "Error: The values may not exceed the field borders!\n";
                        }
                        elseif (! isset($inputX) || ! isset($inputY))
                        {
                            echo "Error: No value set for x or y\n";
                        }
                        else
                        {
                            $currentCellState = $_board->getField($inputX, $inputY);
                            $_board->setField($inputX, $inputY, !$currentCellState);

                            $this->printBoardEditor($_board, $inputX, $inputY);
                        }
                    }
                    else echo "Error: Don't input more than two values!\n";
                }
                else echo "Error: Input the coordinates in this format: <x" . ">,<y" . ">\n";
            }
        }
    }

    /**
     * Print the board to the console and highlights the cell at ($_curX | $_curY) if both values are set
     *
     * @param Board $_board     Current board
     * @param Integer $_curX    X-Coordinate of the cell that shall be highlighted
     * @param Integer $_curY    Y-Coordinate of the cell that shall be highlighted
     */
    public function printBoardEditor($_board, $_curX = null, $_curY = null)
    {
        $bonusDashes = 0;

        if (isset($_curX) && isset($_curY))
        {
            $isHighLight = true;

            if ($_curY == 0) $bonusDashes = 1;
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
}