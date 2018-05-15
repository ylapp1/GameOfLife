<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use TemplateHandler\TemplateListPrinter;
use TemplateHandler\TemplateLoader;
use TemplateHandler\FieldsPlacer;
use Ulrichsg\Getopt;
use Utils\FileSystem\FileSystemReader;

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
     * @var FieldsPlacer $templatePlacer
     */
    private $templatePlacer;

    /**
     * The list of default template names
     *
     * @var String[] $defaultTemplateNames
     */
    private $defaultTemplateNames = array();


    /**
     * TemplateInput constructor.
     *
     * @param String $_templatesBaseDirectory The base directory for default and custom templates
     *
     * @throws \Exception The exception when the default template folder does not exist
     */
    public function __construct(String $_templatesBaseDirectory = null)
    {
        $templatesBaseDirectory = __DIR__ . "/../../../Input/Templates";
        if ($_templatesBaseDirectory !== null) $templatesBaseDirectory = $_templatesBaseDirectory;

        $this->templateListPrinter = new TemplateListPrinter($templatesBaseDirectory);
        $this->templateLoader = new TemplateLoader($templatesBaseDirectory);
        $this->templatePlacer = new FieldsPlacer();

        $fileSystemReader = new FileSystemReader();

        $defaultTemplatePaths = $fileSystemReader->getFileList($templatesBaseDirectory . "/*.txt");
        $this->defaultTemplateNames = array_map(
            function($_arrayEntry)
            {
                return basename($_arrayEntry, ".txt");
            },
            $defaultTemplatePaths
        );

        unset($fileSystemHandler);
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
                    array(null, $defaultTemplateName . "PosX", Getopt::REQUIRED_ARGUMENT, "TemplateInput - X position of the " . $defaultTemplateName),
                    array(null, $defaultTemplateName . "PosY", Getopt::REQUIRED_ARGUMENT, "TemplateInput - Y position of the " . $defaultTemplateName . "\n")
                )
            );
        }

        $_options->addOptions(
            array
            (
                array(null, "template", Getopt::REQUIRED_ARGUMENT, "TemplateInput - The name of the template file that shall be loaded"),
                array(null, "list-templates", Getopt::NO_ARGUMENT, "TemplateInput - Display a list of all templates"),
                array(null, "templatePosX", Getopt::REQUIRED_ARGUMENT, "TemplateInput - X-Position of the top left corner of the template"),
                array(null, "templatePosY", Getopt::REQUIRED_ARGUMENT, "TemplateInput - Y-Position of the top left corner of the template"),
                array(null, "invertTemplate", Getopt::NO_ARGUMENT, "TemplateInput - Inverts the loaded template\n")
            )
        );
    }

    /**
     * Places a template on the board or displays a list of templates.
     *
     * @param Board $_board The board
     * @param Getopt $_options The option list
     *
     * @throws \Exception The exception of FieldsPlacer, TemplateListPrinter or if no template file was specified
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
        if ($_options->getOption("template") !== null)
        {
            $this->placeTemplate($_board, $_options, $_options->getOption("template"), true);
        }
        elseif ($_options->getOption("list-templates") !== null)
        {
            $this->templateListPrinter->printTemplateLists();
        }
        else
        {
            $templateName = $this->getTemplateNameFromLinkedOption($_options);
            if ($templateName) $this->placeTemplate($_board, $_options, $templateName, false);
            else
            {
                if ($_options->getOption("input") !== "template")
                {
                    $randomInput = new RandomInput();
                    $randomInput->fillBoard($_board, new Getopt());
                }
                else throw new \Exception("No template file specified.");
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
                if (strtolower($defaultTemplateName) == strtolower($_options->getOption("input"))) return $defaultTemplateName;
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
     *
     * @throws \Exception The exception when the template could not be loaded or exceeds one of the board borders
     */
    private function placeTemplate(Board $_board, Getopt $_options, String $_templateName, Bool $_isTemplateOption)
    {
        $templateFields = $this->templateLoader->loadTemplate($_templateName);

        $posOptionPrefix = "template";
        if (! $_isTemplateOption) $posOptionPrefix = $_templateName;

        // Get X position
        $templatePosX = ceil(($_board->width() - count($templateFields[0])) / 2);
        if ($_options->getOption($posOptionPrefix . "PosX") !== null)
        {
            $templatePosX = (int)$_options->getOption($posOptionPrefix . "PosX");
        }

        // Get Y position
        $templatePosY =  ceil(($_board->height() - count($templateFields)) / 2);
        if ($_options->getOption($posOptionPrefix . "PosY") !== null)
        {
            $templatePosY = (int)$_options->getOption($posOptionPrefix . "PosY");
        }

        $isDimensionsAdjustment = $this->isDimensionsAdjustment($_options, $_board, $templateFields, $posOptionPrefix);

        $this->templatePlacer->placeTemplate($templateFields, $_board, $templatePosX, $templatePosY, $isDimensionsAdjustment);

        if ($_options->getOption("invertTemplate") !== null) $_board->invertFields();
    }

    /**
     * Returns whether the board dimensions shall be adjusted to be the same like the templates dimensions.
     * If the template position or the board dimensions are specified the function assumes that the user
     * wants to keep the original board dimensions
     *
     * @param Getopt $_options The option list
     * @param Board $_board The board that will be filled
     * @param array $_templateFields The template fields
     * @param String $_posOptionPrefix The pos option prefix
     *
     * @return Bool Indicates whether the board dimensions shall be adjusted to be the same like the templates dimensions
     */
    private function isDimensionsAdjustment(Getopt $_options, Board $_board, array $_templateFields, String $_posOptionPrefix): Bool
    {
        if ($_posOptionPrefix !== "template")
        { // If the template was selected by using --input
            $templatePosX = ceil(($_board->width() - count($_templateFields[0])) / 2);
            $templatePosY =  ceil(($_board->height() - count($_templateFields)) / 2);

            $templateHeight = count($_templateFields);
            $templateWidth = count($_templateFields[0]);

            if (! $this->templatePlacer->isTemplateOutOfBounds($_board, $templateWidth, $templateHeight, $templatePosX, $templatePosY))
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
