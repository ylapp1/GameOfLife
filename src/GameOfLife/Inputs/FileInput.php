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
 * Fills the board with cells whose positions are loaded from a template file.
 *
 * @package Input
 */
class FileInput extends BaseInput
{
    private $fileSystemHandler;
    private $templateDirectory = __DIR__ . "/../../../Input/Templates/";
    private $templateHeight;
    private $templateWidth;


    /**
     * FileInput constructor.
     */
    public function __construct()
    {
        $this->fileSystemHandler = new FileSystemHandler();
    }


    // Getters and Setters

    /**
     * Returns the file system handler of this file input.
     *
     * @return FileSystemHandler    The file system handler
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the file system handler of this file input.
     *
     * @param FileSystemHandler $_fileSystemHandler     The file system handler
     */
    public function setFileSystemHandler(FileSystemhandler $_fileSystemHandler)
    {
        $this->fileSystemHandler = $_fileSystemHandler;
    }

    /**
     * Returns the template directory.
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
     * Returns the height of the template board.
     *
     * @return int  Height of the template board
     */
    public function templateHeight(): int
    {
        return $this->templateHeight;
    }

    /**
     * Sets the height of the template board.
     *
     * @param int $_templateHeight  Height of the template board
     */
    public function setTemplateHeight(int $_templateHeight)
    {
        $this->templateHeight = $_templateHeight;
    }

    /**
     * Returns the width ot the template board.
     *
     * @return int  Width of the template board
     */
    public function templateWidth(): int
    {
        return $this->templateWidth;
    }

    /**
     * Sets the width of the template board.
     *
     * @param int $_templateWidth   Width of the template board
     */
    public function setTemplateWidth(int $_templateWidth)
    {
        $this->templateWidth = $_templateWidth;
    }


    /**
     * Adds FileInputs specific options to the option list.
     *
     * @param Getopt $_options     Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array
            (
                array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration"),
                array(null, "list-templates", Getopt::NO_ARGUMENT, "Display a list of all templates"),
                array(null, "templatePosX", Getopt::REQUIRED_ARGUMENT, "X-Position of the top left corner of the template"),
                array(null, "templatePosY", Getopt::REQUIRED_ARGUMENT, "Y-Position of the top left corner of the template"),
            )
        );
    }

    /**
     * Places the cells on the board.
     *
     * If the template position is specified the function assumes that the user wants to keep the original board dimensions
     *
     * @param Board $_board      The Board
     * @param Getopt $_options   Options (template)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("template") !== null)
        {
            $isDimensionsAdjustment = true;
            $boardCenter = $_board->getCenter();
            $templatePosX = $boardCenter["x"];
            $templatePosY = $boardCenter["y"];

            if ($_options->getOption("templatePosX") !== null)
            {
                $templatePosX = (int)$_options->getOption("templatePosX");
                $isDimensionsAdjustment = false;
            }

            if ($_options->getOption("templatePosY") !== null)
            {
                $templatePosY = (int)$_options->getOption("templatePosY");
                $isDimensionsAdjustment = false;
            }

            $this->placeTemplate($_board, $_options->getOption("template"), $templatePosX, $templatePosY, $isDimensionsAdjustment);
        }
        elseif ($_options->getOption("list-templates") !== null)
        {
            $defaultTemplates = $this->fileSystemHandler->getFileList($this->templateDirectory, ".txt");
            $customTemplates = $this->fileSystemHandler->getFileList($this->templateDirectory . "/Custom", ".txt");

            echo "\n\nDefault templates:\n";
            if (count($defaultTemplates) == 0) echo "  None\n";
            else
            {
                foreach ($defaultTemplates as $index => $templateName)
                {
                    echo "  " . ($index + 1) . ") " . basename($templateName, ".txt") . "\n";
                }
            }

            echo "\nCustom templates:\n";
            if (count($customTemplates) == 0) echo "  None\n";
            else
            {
                foreach ($customTemplates as $index => $templateName)
                {
                    echo " " . ($index + 1) . ") " . basename($templateName, ".txt") . "\n";
                }
            }
        }
        else echo "Error: No template file specified\n";
    }

    /**
     * Load board from txt file.
     *
     * @param string $_templateName     Template name
     *
     * @return array  The template converted to an array
     */
    private function loadTemplate(string $_templateName): array
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

        $templateBoard = array();
        $this->templateHeight = count($lines);
        $this->templateWidth = count(str_split($lines[0]));

        for ($y = 0; $y < count($lines); $y++)
        {
            $templateBoard[$y] = array();
            $cells = str_split($lines[$y]);

            for ($x = 0; $x < count($cells); $x++)
            {
                if ($cells[$x] == "X") $templateBoard[$y][$x] = true;
            }
        }

        return $templateBoard;
    }

    /**
     * Calls loadTemplate() and places the template on the board.
     *
     * @param Board $_board                     Board on which the template will be placed
     * @param string $_template                 Template name
     * @param int $_posX                        X-Coordinate of the top left corner of the template position
     * @param int $_posY                        Y-Coordinate of the top right corner of the template position
     * @param bool $_isDimensionsAdjustment     Indicates that the board dimensions shall be reconfigured to match the template width and height
     */
    private function placeTemplate(Board $_board, string $_template, int $_posX, int $_posY, bool $_isDimensionsAdjustment)
    {
        $templateBoard = $this->loadTemplate($_template);
        $board = $_board->currentBoard();

        if ($_isDimensionsAdjustment)
        {
            $_board->setWidth($this->templateWidth);
            $_board->setHeight($this->templateHeight);
            $board = $templateBoard;
        }
        else
        {
            if ($this->isTemplateOutOfBounds($_board->width(), $_board->height(), $_posX, $_posY))
            {
                echo "Error, the template may not exceed the field borders!\n";
            }
            else
            {
                for ($y = 0; $y < $this->templateHeight; $y++)
                {
                    for ($x = 0; $x < $this->templateWidth; $x++)
                    {
                        if (isset($templateBoard[$y][$x])) $board[$y + $_posY][$x + $_posX] = true;
                    }
                }
            }
        }

        $_board->setCurrentBoard($board);
    }

    /**
     * Checks whether the template is out of bounds.
     *
     * Uses the class attributes "templateWidth" and "templateHeight" to check the template dimensions
     *
     * @param int $_boardWidth  Board width
     * @param int $_boardHeight Board height
     * @param int $_posX        X-Coordinate of the top left border of the template
     * @param int $_posY        Y-Coordinate of the top left border of the template
     *
     * @return bool     True: Template is out of bounds
     *                  False: Template is not out of bounds
     */
    private function isTemplateOutOfBounds(int $_boardWidth, int $_boardHeight, int $_posX, int $_posY): bool
    {
        if ($_posX < 0 ||
            $_posY < 0 ||
            $_posX + $this->templateWidth > $_boardWidth ||
            $_posY + $this->templateHeight > $_boardHeight)
        {
            return true;
        }
        else return false;
    }
}