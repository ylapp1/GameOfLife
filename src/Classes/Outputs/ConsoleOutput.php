<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class ConsoleOutput
 *
 * @package Output
 */
class ConsoleOutput extends BaseOutput
{
    /**
     * add output specific options to the option list
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions($_options)
    {
    }

    /**
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput($_options, $_board)
    {
        echo "\nStarting the simulation ...\n";
    }

    /**
     * Output one game step
     *
     * @param Board $_board     Current board
     */
    public function outputBoard($_board)
    {
        echo "\n\n";

        $gameStepString = "Game step: " . ($_board->gameStep() + 1);
        $paddingLeft = ceil(($_board->width() - strlen($gameStepString)) / 2) + 1;

        for ($i = 0; $i < $paddingLeft; $i++)
        {
            echo " ";
        }
        echo $gameStepString . "\n";

        // output upper border
        echo "╔";
        for ($x = 0; $x < $_board->width(); $x++)
        {
            echo "═";
        }
        echo "╗";

        /*echo "\n║Game Step: ";
        echo "\n╠";
        for ($x = 0; $x < $_board->width(); $x++)
        {
            echo "═";
        }
        echo "╣";*/

        foreach ($_board->currentBoard() as $line)
        {
            echo "\n║";
            foreach ($line as $cell)
            {
                if ($cell === true) echo "☻";
                else echo " ";
            }
            echo "║";
        }

        // Output bottom border
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
     * Finish output (write to file)
     */
    public function finishOutput()
    {
        echo "\nSimulation finished. All cells are dead or a repeating pattern was detected.\n\n";
    }
}