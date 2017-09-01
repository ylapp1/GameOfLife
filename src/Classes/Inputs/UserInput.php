<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 */

namespace Input;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class UserInput
 *
 * @package Input
 */
class UserInput extends BaseInput
{
    /**
     * Adds UserInputs specific options to the option list
     *
     * @param Getopt $_options  Option list to which the objects options are added
     */
    function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "edit", Getopt::NO_ARGUMENT, "Edit a template"))
        );
    }

    /**
     * Catches input from keyboard to create an own generation
     * Put Numbers in like 5,5 to set to true
     * or -5,-5 to set to false
     *
     * @param \GameOfLife\Board $_board
     * @param Getopt $_options
     */
    function fillBoard($_board, $_options)
    {
        if ($_options->getOption("edit"))
        {
            $fileInput = new FileInput();
            $fileInput->fillBoard($_board, $_options);
            $this->printBoardEditor($_board);
        }

        echo "Set the coordinates for the living fields as below:\n";
        echo "<"."number>,<number".">\n<"."-number>,<-number".">\n";
        echo "The stroke before the number sets a wrongly set field to false\n";
        echo "and after that press <"."Enter>. The first number stands for X and the second number for Y\n";
        echo "The game starts when you type \"start\" in a new line and press <"."Enter>\n";
        echo "You can save your board configuration before starting the simulation by typing \"save\"\n";
        echo "Let's Go:\n";

        $fileOpen = fopen('php://stdin','r') or die($php_errormsg);
        $lastLine = false;

        $inputX = null;
        $inputY = null;

        while (! $lastLine) {
            $nextLine = fgets($fileOpen,1024);
            if (stristr($nextLine, "start"))
            {
                $lastLine = true;
            }
            elseif (stristr($nextLine, "save"))
            {
                // fetch config name
                $config = explode(" ", $nextLine);

                if (count($config) == 2)
                {
                    $config[1] = rtrim($config[1], "\n\r");

                    $fileDirectory = __DIR__ . "/../../../Input/Templates/Custom/";
                    $fileName = $fileDirectory . $config[1] . ".txt";

                    // Create directory if it doesn't exist
                    if (! file_exists($fileDirectory)) mkdir($fileDirectory, 0777);

                    file_put_contents($fileName, $_board);

                    $lastLine = true;
                }
                else echo "Error: No filename specified!";
            }
            else
            {
                if (stristr($nextLine, "exit")) die();
                if (stristr($nextLine, ","))
                {
                    $inputSplits = explode(",", $nextLine);
                    if (count($inputSplits) == 2)
                    {
                        if (stristr($inputSplits[0], "-") && stristr($inputSplits[1], "-"))
                        {
                            $trimX = trim($inputSplits[0], "-");
                            $trimY = trim($inputSplits[1], "-");

                            if ($trimX > $_board->width() - 1 || $trimY > $_board->height() - 1)
                            {
                                echo "The numbers may not exceed the field borders!";
                            }
                            else
                            {
                                $inputX = (int)$trimX;
                                $inputY = (int)$trimY;

                                $_board->setField($inputX, $inputY, false);
                            }
                        }
                        else
                        {
                            if ($inputSplits[0] > $_board->width() - 1 ||
                                $inputSplits[0] < 0 ||
                                $inputSplits[1] > $_board->height() - 1 ||
                                $inputSplits[1] < 0)
                            {
                                echo "The numbers may not exceed the field borders!";
                            }
                            else
                            {
                                $inputX = (int)$inputSplits[0];
                                $inputY = (int)$inputSplits[1];

                                $_board->setField($inputX, $inputY, true);
                            }
                        }
                    }
                    else
                    {
                        echo "Don't give me more than two numbers!";
                    }
                }
                $this->printBoardEditor($_board, $inputX, $inputY);
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
        if ($_curX != null && $_curY != null) $isHighLight = true;
        else $isHighLight = false;


        if ($isHighLight)
        {
            // Output last set cell x-coordinate
            echo "\n  ";
            for ($i = 0; $i < $_board->width() + 2; $i++)
            {
                if ($i == $_curX) echo $_curX;
                else echo " ";
            }
        }


        // print upper border
        echo "\n ";
        for ($i = 0; $i < $_board->width(); $i++)
        {
            echo "-";
        }

        if ($isHighLight) echo "--";


        // print board
        for ($y = 0; $y < $_board->height(); $y++)
        {
            echo "\n|";

            // Output lines above and below the last set cell
            if (($y == $_curY || $y == $_curY + 1) && $isHighLight)
            {
                for ($i = 0; $i < $_board->width() + 2; $i++)
                {
                    echo "-";
                }
                echo "|\n|";
            }


            // Output cells
            for ($x = 0; $x < $_board->width(); $x++)
            {
                // Output lines left and right from the last set cell
                if (($x == $_curX || $x == $_curX + 1) && $isHighLight)
                {
                    echo "|";
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
        echo "\n ";
        for ($i = 0; $i < $_board->width(); $i++)
        {
            echo "-";
        }

        if ($isHighLight) echo "--";
        echo "\n";
    }
}