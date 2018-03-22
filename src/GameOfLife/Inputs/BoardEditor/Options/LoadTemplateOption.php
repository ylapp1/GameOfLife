<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;
use TemplateHandler\TemplateLoader;
use TemplateHandler\TemplatePlacer;

/**
 * Loads a template and places it on the board.
 */
class LoadTemplateOption extends BoardEditorOption
{
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
     * LoadTemplateOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "load";
        $this->aliases = array("loadTemplate", "placeTemplate");
        $this->callback = "loadTemplate";
        $this->description = "Clears the board";
        $this->arguments = array("template name");

        $this->templateLoader = new TemplateLoader($this->parentBoardEditor->templateDirectory());
        $this->templatePlacer = new TemplatePlacer();
    }


    /**
     * Loads a template and places it on the board.
     *
     * @param String $_templateName The template name
     * @param String $_posX The X-Position of the top left corner of the template on the board
     * @param String $_posY The Y-Position of the top left corner of the template on the board
     * @param String $_adjustDimensions Indicates whether the board dimensions shall be adjusted to match the template dimensions
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the template file could not be found or the input value is invalid
     */
    public function loadTemplate($_templateName, $_posX = null, $_posY = null, $_adjustDimensions = null): bool
    {
        $templateFields = $this->templateLoader->loadTemplate($_templateName);

        if ($_adjustDimensions) $adjustDimensions = (bool)$_adjustDimensions;
        else
        {
            echo "Adjust the board to match the template dimensions? (Yes|No): ";

            $userDecision = $this->parentBoardEditor->readInput("php://stdin");
            if (stristr($userDecision, "yes") || stristr($userDecision, "y")) $adjustDimensions = true;
            else $adjustDimensions = false;
        }

        if (! $adjustDimensions)
        {
            if ($_posX) $posX = $_posX;
            else
            {
                $posX = $this->parentBoardEditor->readCoordinate(
                    "X",
                    "top left border of the template on the board",
                    0,
                    $this->parentBoardEditor->board()->width()
                );
            }

            if ($_posY) $posY = $_posY;
            else
            {
                $posY = $this->parentBoardEditor->readCoordinate(
                    "Y",
                    "top left border of the template on the board",
                    0,
                    $this->parentBoardEditor->board()->height()
                );
            }
        }
        else
        {
            $posX = 0;
            $posY = 0;
        }

        $this->templatePlacer->placeTemplate($templateFields, $this->parentBoardEditor->board(), $posX, $posY, $adjustDimensions);
        $this->parentBoardEditor->output()->outputBoard($this->parentBoardEditor->board());
        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
