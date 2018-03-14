<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use TemplateHandler\TemplateLoader;
use TemplateHandler\TemplatePlacer;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Fills the board with cells whose positions are loaded from a template file.
 *
 * Call addOptions($_options) to add the FileInput options to a Getopt object
 * Call fillBoard($_board) to place a template on the board
 */
class TemplateInput extends BaseInput
{
    private $fileSystemHandler;
    private $templateDirectory;
    private $templateLoader;
    private $templatePlacer;
    private $defaultTemplateNames = array();


    /**
     * FileInput constructor.
     *
     * @param String $_templateDirectory The template directory from which templates shall be loaded
     */
    public function __construct(String $_templateDirectory = null)
    {
        if ($_templateDirectory === null) $this->templateDirectory = __DIR__ . "/../../../Input/Templates/";
        else $this->templateDirectory = $_templateDirectory;

        $this->templateLoader = new TemplateLoader($this->templateDirectory);
        $this->templatePlacer = new TemplatePlacer();
        $this->fileSystemHandler = new FileSystemHandler();

        $this->defaultTemplateNames = array_map(
            function($_arrayEntry)
            {
                return basename($_arrayEntry, ".txt");
            },
            $this->fileSystemHandler->getFileList($this->templateDirectory . "/*.txt")
        );
    }


    /**
     * Returns the template directory.
     *
     * @return String Template directory
     */
    public function templateDirectory(): String
    {
        return $this->templateDirectory;
    }

    /**
     * Sets the template directory.
     *
     * @param String $_templateDirectory Template directory
     */
    public function setTemplateDirectory(String $_templateDirectory)
    {
        $this->templateDirectory = $_templateDirectory;
    }

    /**
     * Returns the template loader.
     *
     * @return TemplateLoader Template loader
     */
    public function templateLoader(): TemplateLoader
    {
        return $this->templateLoader;
    }

    /**
     * Sets the template loader
     *
     * @param TemplateLoader $_templateLoader Template loader
     */
    public function setTemplateLoader(TemplateLoader $_templateLoader)
    {
        $this->templateLoader = $_templateLoader;
    }

    /**
     * Returns the template placer.
     *
     * @return TemplatePlacer Template placer
     */
    public function templatePlacer(): TemplatePlacer
    {
        return $this->templatePlacer;
    }

    /**
     * Sets the template placer.
     *
     * @param TemplatePlacer $_templatePlacer Template placer
     */
    public function setTemplatePlacer(TemplatePlacer $_templatePlacer)
    {
        $this->templatePlacer = $_templatePlacer;
    }


    /**
     * Adds FileInput options to a Getopt object.
     *
     * @param Getopt $_options Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        foreach ($this->defaultTemplateNames as $defaultTemplateName)
        {
            $_options->addOptions(
                array (
                    array(null, $defaultTemplateName . "PosX", Getopt::REQUIRED_ARGUMENT, "X position of the " . $defaultTemplateName),
                    array(null, $defaultTemplateName . "PosY", Getopt::REQUIRED_ARGUMENT, "Y position of the " . $defaultTemplateName)
                )
            );
        }

        $_options->addOptions(
            array
            (
                array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration"),
                array(null, "list-templates", Getopt::NO_ARGUMENT, "Display a list of all templates"),
                array(null, "templatePosX", Getopt::REQUIRED_ARGUMENT, "X-Position of the top left corner of the template"),
                array(null, "templatePosY", Getopt::REQUIRED_ARGUMENT, "Y-Position of the top left corner of the template"),
                array(null, "invertTemplate", Getopt::NO_ARGUMENT, "Inverts the loaded template")
            )
        );
    }

    /**
     * Places a template on the board or displays a list of templates.
     *
     * @param Board $_board The Board
     * @param Getopt $_options Options (template, list-templates)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("template") !== null) $this->placeTemplate($_board, $_options);
        elseif ($_options->getOption("list-templates") !== null)
        {
            $fileSystemHandler = new FileSystemHandler();

            $defaultTemplates = $fileSystemHandler->getFileList($this->templateDirectory . "/*.txt");
            $customTemplates = $fileSystemHandler->getFileList($this->templateDirectory . "/Custom/*.txt");

            echo $this->listTemplates("Default templates", $defaultTemplates);
            echo $this->listTemplates("Custom templates", $customTemplates);
        }
        else
        {
            $templateName = false;

            foreach ($this->defaultTemplateNames as $defaultTemplateName)
            {
                if ($_options->getOption("input") !== null)
                {
                    if ($_options->getOption("input") == $defaultTemplateName) $templateName = $defaultTemplateName;
                }
                else
                {
                    if ($_options->getOption($defaultTemplateName . "PosX") !== null ||
                        $_options->getOption($defaultTemplateName . "PosY") !== null)
                    {
                        $templateName = $defaultTemplateName;
                    }
                }

                if ($templateName) break;
            }

            if ($templateName) $this->placeTemplate($_board, $_options, $templateName);
            else
            {
                if ($_options->getOption("input") !== null && $_options->getOption("input") !== "template")
                {
                    $randomInput = new RandomInput();
                    $randomInput->fillBoard($_board, new Getopt());
                }
                else echo "Error: No template file specified\n";
            }
        }
    }

    /**
     * Generates an output string from a list of templates.
     *
     * @param String $_title Title of the list
     * @param String[] $_templateList List of template names
     *
     * @return String Output string
     */
    private function listTemplates(String $_title, array $_templateList): String
    {
        $outputString = "\n" . $_title . ":\n";
        if (count($_templateList) == 0) $outputString .= "  None\n";
        else
        {
            foreach ($_templateList as $index => $templateName)
            {
                $outputString .= "  " . ($index + 1) . ") " . basename($templateName, ".txt") . "\n";
            }
        }

        return $outputString;
    }

    /**
     * Places the template on the board.
     *
     * If the template position is specified the function assumes that the user wants to keep the original board dimensions
     *
     * @param Board $_board Board on which the template will be placed
     * @param Getopt $_options Option list
     * @param String $_templateName The name of the template
     */
    private function placeTemplate(Board $_board, Getopt $_options, String $_templateName = null)
    {
        $isDimensionsAdjustment = true;

        $boardCenter = $_board->getCenter();
        $templatePosX = $boardCenter["x"];
        $templatePosY = $boardCenter["y"];

        $posXPrefix = "template";
        $posYPrefix = "template";

        if ($_templateName)
        {
            $posXPrefix = $_templateName;
            $posYPrefix = $_templateName;
        }

        if ($_options->getOption($posXPrefix . "PosX") !== null)
        {
            $templatePosX = (int)$_options->getOption($posXPrefix . "PosX");
            $isDimensionsAdjustment = false;
        }

        if ($_options->getOption($posYPrefix . "PosY") !== null)
        {
            $templatePosY = (int)$_options->getOption($posYPrefix . "PosY");
            $isDimensionsAdjustment = false;
        }

        if ($_options->getOption("width") !== null || $_options->getOption("height") !== null) $isDimensionsAdjustment = false;

        if ($_templateName) $templateName = $_templateName;
        else $templateName = $_options->getOption("template");

        $template = $this->templateLoader->loadTemplate($_board, $templateName);

        if ($template == false) echo "Error: Template file not found!\n";
        else
        {
            if ($_templateName)
            {
                if (! $this->templatePlacer->isTemplateOutOfBounds($_board, $template, $templatePosX, $templatePosY))
                {
                    $isDimensionsAdjustment = false;
                }
            }

            $result = $this->templatePlacer->placeTemplate($template, $_board, $templatePosX, $templatePosY, $isDimensionsAdjustment);

            if ($result == false) echo "Error, the template may not exceed the field borders!\n";
            else
            {
                if ($_options->getOption("invertTemplate") !== null) $_board->invertBoard();
            }
        }
    }
}
