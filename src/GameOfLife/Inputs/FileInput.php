<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Fills the board with cells whose positions are loaded from a template file
 *
 * @package Input
 */
class FileInput extends BaseInput
{
    private $templateDirectory = __DIR__ . "/../../../Input/Templates/";
    private $templateHeight;
    private $templateWidth;


    // Getters and Setters

    /**
     * Returns the template directory
     *
     * @return string   Template directory
     */
    public function templateDirectory(): string
    {
        return $this->templateDirectory;
    }

    /**
     * Sets the template directory
     *
     * @param string $_templateDirectory    Template directory
     */
    public function setTemplateDirectory(string $_templateDirectory)
    {
        $this->templateDirectory = $_templateDirectory;
    }

    /**
     * Returns the height of the template board
     *
     * @return int  Height of the template board
     */
    public function templateHeight(): int
    {
        return $this->templateHeight;
    }

    /**
     * Sets the height of the template board
     *
     * @param int $_templateHeight  Height of the template board
     */
    public function setTemplateHeight(int $_templateHeight)
    {
        $this->templateHeight = $_templateHeight;
    }

    /**
     * returns the width ot the template board
     *
     * @return int  Width of the template board
     */
    public function templateWidth(): int
    {
        return $this->templateWidth;
    }

    /**
     * Sets the width of the template board
     *
     * @param int $_templateWidth   Width of the template board
     */
    public function setTemplateWidth(int $_templateWidth)
    {
        $this->templateWidth = $_templateWidth;
    }


    /**
     * Adds FileInputs specific options to the option list
     *
     * @param Getopt $_options     Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array
            (
                array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration")
            )
        );
    }

    /**
     * Places the cells on the board
     *
     * @param Board $_board      The Board
     * @param Getopt $_options   Options (template)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("template"))
        {
            $board = $this->loadTemplate($_options->getOption("template"));

            // Reconfigure the board dimensions
            $_board->setHeight($this->templateHeight);
            $_board->setWidth($this->templateWidth);
            $_board->setCurrentBoard($board);
        }
        else echo "Error: No template file specified\n";
    }

    /**
     * Load board from txt file
     *
     * @param string $_templateName     Template name
     *
     * @return array  The board
     */
    public function loadTemplate(string $_templateName): array
    {
        $fileNameOfficial =  $this->templateDirectory . "/" . $_templateName . ".txt";
        $fileNameCustom = $this->templateDirectory . "/Custom/" . $_templateName . ".txt";

        // check whether template exists
        if (file_exists($fileNameOfficial)) $fileName = $fileNameOfficial;
        elseif (file_exists($fileNameCustom)) $fileName = $fileNameCustom;
        else
        {
            echo "Error: Template file not found!\n";
            $this->templateHeight = 0;
            $this->templateWidth = 0;
            return array();
        }

        // Read template
        $fileSystemHandler = new FileSystemHandler();
        $lines = $fileSystemHandler->readFile($fileName);

        $board = array();
        $this->templateHeight = count($lines);
        $this->templateWidth = count(str_split($lines[0]));

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