<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * Prints boards to the console.
 *
 * @package Output
 */
class ConsoleOutput extends BaseOutput
{
    /**
     * Initializes the output.
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        echo "\nStarting the simulation ...\n";
    }

    /**
     * Outputs one game step.
     *
     * @param Board $_board     Current board
     */
    public function outputBoard(Board $_board)
    {
        echo "\n\n";

        $gameStepString = "Game step: " . ($_board->gameStep() + 1);
        $paddingLeft = ceil(($_board->width() - strlen($gameStepString)) / 2) + 1;

        $padding = str_pad("", $paddingLeft);
        echo $padding . $gameStepString . "\n";

        // output upper border
        echo "╔";
        for ($x = 0; $x < $_board->width(); $x++)
        {
            echo "═";
        }
        echo "╗";

        // output board
        for ($y = 0; $y < $_board->height(); $y++)
        {
            echo "\n║";
            for ($x = 0; $x < $_board->width(); $x++)
            {
                if ($_board->getField($x, $y)) echo "☻";
                else echo " ";
            }
            echo "║";
        }

        // output bottom border
        echo "\n╚";
        for ($x = 0; $x < $_board->width(); $x++)
        {
            echo "═";
        }
        echo "╝\n";

        // wait for 0.1 seconds before printing the next board
        usleep(100000);
    }

    /**
     * Prints a message saying that the simulation is finished.
     */
    public function finishOutput()
    {
        echo "\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n";
    }
}