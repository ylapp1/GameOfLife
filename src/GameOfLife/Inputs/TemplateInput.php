<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use TemplateHandler\Template;
use TemplateHandler\TemplateListPrinter;
use TemplateHandler\TemplateLoader;
use TemplateHandler\TemplatePlacer;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Fills a board with fields that are loaded from a template file.
 */
class TemplateInput extends BaseInput
{
    /**
     * The template list printer
     *
     * @var TemplateListPrinter $templateListPrinter
     */
    private $templateListPrinter;

    /**
     * The template loader
     *
     * @var TemplateLoader $templateLoader
     */
    private $templateLoader;

    /**
     * The template placer
     *
     * @var TemplatePlacer $templatePlacer
     */
    private $templatePlacer;

    /**
     * The list of default template names
     *
     * @var String[] $defaultTemplateNames
     */
    private $defaultTemplateNames = array();


    /**
     * FileInput constructor.
     *
     * @param String $_templatesBaseDirectory The base directory for default and custom templates
     */
    public function __construct(String $_templatesBaseDirectory = null)
    {
        $templatesBaseDirectory = __DIR__ . "/../../../Input/Templates/";
        if ($_templatesBaseDirectory !== null) $templatesBaseDirectory = $_templatesBaseDirectory;

        $this->templateListPrinter = new TemplateListPrinter($templatesBaseDirectory);
        $this->templateLoader = new TemplateLoader($templatesBaseDirectory);
        $this->templatePlacer = new TemplatePlacer();
        $fileSystemHandler = new FileSystemHandler();

        $defaultTemplatePaths = $fileSystemHandler->getFileList($templatesBaseDirectory . "/*.txt");
        $this->defaultTemplateNames = array_map(
            function($_arrayEntry)
            {
                return basename($_arrayEntry, ".txt");
            },
            $defaultTemplatePaths
        );
    }


    /**
     * Adds TemplateInputs options to a Getopt object.
     *
     * @param Getopt $_options The option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        // Generate the options for the default templates
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
     * @param Board $_board The board
     * @param Getopt $_options The option list
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("template") !== null) $this->placeTemplate($_board, $_options, $_options->getOption("template"), true);
        elseif ($_options->getOption("list-templates") !== null) $this->templateListPrinter->printTemplateLists();
        else
        {
            $templateName = $this->getTemplateNameFromLinkedOption($_options);
            if ($templateName) $this->placeTemplate($_board, $_options, $templateName, false);
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
     * Tries to find the template name by checking whether an option is set that is linked to a template.
     *
     * @param Getopt $_options The option list
     *
     * @return String|Bool The template name or false
     */
    private function getTemplateNameFromLinkedOption(Getopt $_options)
    {
        foreach ($this->defaultTemplateNames as $defaultTemplateName)
        {
            if ($_options->getOption("input") !== null)
            {
                if ($_options->getOption("input") == $defaultTemplateName) return $defaultTemplateName;
            }
            else
            {
                if ($_options->getOption($defaultTemplateName . "PosX") !== null ||
                    $_options->getOption($defaultTemplateName . "PosY") !== null)
                {
                    return $defaultTemplateName;
                }
            }
        }

        return false;
    }

    /**
     * Places the template on the board.
     *
     * @param Board $_board The board on which the template will be placed
     * @param Getopt $_options The option list
     * @param String $_templateName The name of the template
     * @param Bool $_isTemplateOption Indicates whether this function was called because the option "template" was set
     */
    private function placeTemplate(Board $_board, Getopt $_options, String $_templateName, Bool $_isTemplateOption)
    {
        $template = $this->templateLoader->loadTemplate($_templateName);
        if ($template == false)
        {
            echo "Error: Template file not found!\n";
            return;
        }

        $posOptionPrefix = "template";
        if (! $_isTemplateOption) $posOptionPrefix = $_templateName;

        $boardCenter = $_board->getCenter();

        // Get X position
        $templatePosX = $boardCenter["x"];
        if ($_options->getOption($posOptionPrefix . "PosX") !== null)
        {
            $templatePosX = (int)$_options->getOption($posOptionPrefix . "PosX");
        }

        // Get Y position
        $templatePosY = $boardCenter["y"];
        if ($_options->getOption($posOptionPrefix . "PosY") !== null)
        {
            $templatePosY = (int)$_options->getOption($posOptionPrefix . "PosY");
        }

        $isDimensionsAdjustment = $this->isDimensionsAdjustment($_options, $_board, $template, $posOptionPrefix);

        $result = $this->templatePlacer->placeTemplate($template, $_board, $templatePosX, $templatePosY, $isDimensionsAdjustment);
        if ($result == false) echo "Error, the template may not exceed the field borders!\n";
        elseif ($_options->getOption("invertTemplate") !== null) $_board->invertBoard();
    }

    /**
     * Returns whether the board dimensions shall be adjusted to be the same like the templates dimensions.
     * If the template position or the board dimensions are specified the function assumes that the user
     * wants to keep the original board dimensions
     *
     * @param Getopt $_options The option list
     * @param Board $_board The board that will be filled
     * @param Template $_template The template
     * @param String $_posOptionPrefix The pos option prefix
     *
     * @return Bool Indicates whether the board dimensions shall be adjusted to be the same like the templates dimensions
     */
    private function isDimensionsAdjustment(Getopt $_options, Board $_board, Template $_template, String $_posOptionPrefix): Bool
    {
        if ($_posOptionPrefix !== "template")
        { // If the template was selected by using --input
            $templatePosX = $_board->getCenter()["x"];
            $templatePosY = $_board->getCenter()["y"];

            if (! $this->templatePlacer->isTemplateOutOfBounds($_board, $_template, $templatePosX, $templatePosY))
            {
                return false;
            }
        }

        if ($_options->getOption($_posOptionPrefix . "PosX") !== null ||
            $_options->getOption($_posOptionPrefix . "PosY") !== null ||
            $_options->getOption("width") !== null ||
            $_options->getOption("height") !== null)
        {
            return false;
        }

        return true;
    }
}
