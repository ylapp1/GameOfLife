<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class FileInput
 *
 * @package Input
 */
class FileInput extends BaseInput
{
    /**
     * Adds object specific options
     *
     * @param Getopt $_options     Options to which the object specific options shall be added
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration")
            )
        );
    }


    /**
     * Fills a given board with cells
     *
     * @param Board $_board      The board which shall be filled with cells
     * @param Getopt $_options    Object specific options (e.g. posX, posY, fillPercent)
     */
    public function fillBoard($_board, $_options)
    {
        $template = $_options->getOption("template");

        if ($template == null)
        {
            echo "Error: No template file specified\n";
        }
        else
        {
            $fileName = __DIR__ . "/../../Templates/" . $template . ".txt";

            if (file_exists($fileName))
            {
                $config = file_get_contents($fileName);
                $configLines = explode("\n", $config);

                $_board->setHeight(count($configLines));
                $_board->setWidth(count(str_split($configLines[0])) - 1);
                $_board->setCurrentBoard($_board->initializeEmptyBoard());

                for ($y = 0; $y < $_board->height(); $y++)
                {
                    $cells = str_split($configLines[$y]);

                    for ($x = 0; $x < $_board->width(); $x++)
                    {
                        if ($cells[$x] == "o") $_board->setField($x, $y, true);
                        else $_board->setField($x, $y, false);
                    }
                }
            }
            else echo "Error: Template file not found!\n";
        }
    }
}