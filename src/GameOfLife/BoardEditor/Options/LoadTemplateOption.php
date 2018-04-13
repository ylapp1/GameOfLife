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
use TemplateHandler\FieldsPlacer;

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
     * The fields placer
     *
     * @var FieldsPlacer $fieldsPlacer
     */
    private $fieldsPlacer;


    /**
     * LoadTemplateOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "load",
            array("loadTemplate", "placeTemplate"),
            "loadTemplate",
            "Clears the board",
            array(
                "Template name" => "String",
                "Adjust board dimensions" => "Bool",
                "X-Coordinate (left border)" => "int|1=bool,1",
                "Y-Coordinate (top border)" => "int|1=bool,1"
            )
        );

        $this->templateLoader = new TemplateLoader($this->parentBoardEditor->templateDirectory());
        $this->fieldsPlacer = new FieldsPlacer();
    }


    /**
     * Loads a template and places it on the board.
     *
     * @param String $_templateName The template name
     * @param Bool $_adjustDimensions Indicates whether the board dimensions shall be adjusted to match the template dimensions
     * @param int $_posX The X-Position of the top left corner of the template on the board
     * @param int $_posY The Y-Position of the top left corner of the template on the board
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the template file could not be found or the input value is invalid
     */
    public function loadTemplate(String $_templateName, Bool $_adjustDimensions, int $_posX = 0, int $_posY = 0): bool
    {
        $templateFields = $this->templateLoader->loadTemplate($_templateName);

        if (! $_adjustDimensions)
        {
            $this->parentBoardEditor->checkCoordinate($_posX, "X", 0, $this->parentBoardEditor->board()->width());
            $this->parentBoardEditor->checkCoordinate($_posY, "Y", 0, $this->parentBoardEditor->board()->height());
        }

        $this->fieldsPlacer->placeTemplate($templateFields, $this->parentBoardEditor->board(), $_posX, $_posY, $_adjustDimensions);
        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
