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
use GameOfLife\FileSystemHandler;

/**
 * Class FileInput
 *
 * Fills the board with cells whose positions are loaded from template files
 *
 * @package Input
 */
class FileInput extends BaseInput
{
    private $newBoardHeight;
    private $newBoardWidth;
    private $templateDirectory = __DIR__ . "/../../../Input/Templates/";

    /**
     * @return int
     */
    public function newBoardHeight()
    {
        return $this->newBoardHeight;
    }

    /**
     * @param int $_newBoardHeight
     */
    public function setNewBoardHeight($_newBoardHeight)
    {
        $this->newBoardHeight = $_newBoardHeight;
    }

    /**
     * @return int
     */
    public function newBoardWidth()
    {
        return $this->newBoardWidth;
    }

    /**
     * @param int $_newBoardWidth
     */
    public function setNewBoardWidth($_newBoardWidth)
    {
        $this->newBoardWidth = $_newBoardWidth;
    }

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
        if (! isset($template)) echo "Error: No template file specified\n";
        else
        {
            $board = $this->loadTemplate($template);

            // Reconfigure the board dimensions
            $_board->setHeight($this->newBoardHeight);
            $_board->setWidth($this->newBoardWidth);
            $_board->setCurrentBoard($board);
        }
    }

    /**
     * Load cell configuration from txt file
     *
     * @param string $_template     Template name
     *
     * @return array                The board array
     */
    public function loadTemplate($_template)
    {
        $fileNameOfficial =  $this->templateDirectory . "/" . $_template . ".txt";
        $fileNameCustom = $this->templateDirectory . "/Custom/" . $_template . ".txt";

        // check whether template exists
        if (file_exists($fileNameOfficial)) $fileName = $fileNameOfficial;
        elseif (file_exists($fileNameCustom)) $fileName = $fileNameCustom;
        else
        {
            echo "Error: Template file not found!\n";
            return null;
        }

        // Read template
        $fileSystemHandler = new FileSystemHandler();
        $lines = $fileSystemHandler->readFile($fileName);
        $board = array();

        $this->newBoardHeight = count($lines);
        $this->newBoardWidth = count(str_split($lines[0]));

        for ($y = 0; $y < count($lines); $y++)
        {
            $board[$y] = array();
            $cells = str_split($lines[$y]);

            for ($x = 0; $x < count($cells); $x++)
            {
                if ($cells[$x] == "X") $board[$y][$x] = true;
            }
        }

        return $board;
    }
}