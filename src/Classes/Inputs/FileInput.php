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
 * Fills the board with cells whose positions are loaded from template files
 *
 * @package Input
 */
class FileInput extends BaseInput
{
    /**
     * Adds FileInputs specific options to the option list
     *
     * @param Getopt $_options     Option list to which the objects options are added
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration"))
        );
    }

    /**
     * Places the cells on the board
     *
     * @param Board $_board      The Board
     * @param Getopt $_options   Options (template)
     */
    public function fillBoard($_board, $_options)
    {
        // fetch options
        $template = $_options->getOption("template");

        // return error if template name is not set
        if ($template == null) echo "Error: No template file specified\n";
        else
        {
            $templateDirectory = __DIR__ . "/../../../Input/Templates/";
            $fileNameOfficial =  $templateDirectory . $template . ".txt";
            $fileNameCustom = $templateDirectory . "/Custom/" . $template . ".txt";
            $fileName = "";

            // check whether template exists
            if (file_exists($fileNameOfficial)) $fileName = $fileNameOfficial;
            elseif (file_exists($fileNameCustom)) $fileName = $fileNameCustom;
            else echo "Error: Template file not found!\n";


            // Read template
            $lines = file($fileName, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

            // Reconfigure the board dimensions
            $_board->setHeight(count($lines));
            $_board->setWidth(count(str_split($lines[0])));
            $_board->setCurrentBoard($_board->initializeEmptyBoard());

            // Set the cells
            for ($y = 0; $y < $_board->height(); $y++)
            {
                $cells = str_split($lines[$y]);

                for ($x = 0; $x < $_board->width(); $x++)
                {
                    if ($cells[$x] == "o") $_board->setField($x, $y, true);
                    else $_board->setField($x, $y, false);
                }
            }
        }
    }
}