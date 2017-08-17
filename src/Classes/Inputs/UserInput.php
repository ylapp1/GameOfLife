<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 */

namespace Input;

use Ulrichsg\Getopt;

/**
 * Class UserInput
 *
 * @package Input
 */
class UserInput extends BaseInput
{
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

                    $fileName = __DIR__ . "\\..\\..\\Templates\\" . $config[1] . ".txt";

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
                                $_board->setField((int)$trimX, (int)$trimY, false);
                                $inputX = (int)$trimX;
                                $inputY = (int)$trimY;
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
                                $_board->setField((int)$inputSplits[0], (int)$inputSplits[1], true);
                                $inputX = (int)$inputSplits[0];
                                $inputY = (int)$inputSplits[1];
                            }
                        }
                    }
                    else
                    {
                        echo "Don't give me more than two numbers!";
                    }
                }
                $_board->printBoardEditor($inputX, $inputY);
            }
        }
    }

    /**
     * Add input specific options
     *
     * @param Getopt $_options
     */
    function addOptions($_options)
    {

    }
}