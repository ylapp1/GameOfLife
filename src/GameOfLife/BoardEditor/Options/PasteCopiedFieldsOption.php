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
use TemplateHandler\FieldsPlacer;

/**
 * Places the cached copied fields on the board.
 */
class PasteCopiedFieldsOption extends BoardEditorOption
{
    /**
     * The fields placer
     *
     * @var FieldsPlacer $fieldsPlacer
     */
    private $fieldsPlacer;


    /**
     * PasteCopiedFieldsOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "paste",
            array("pastedCopiedFields"),
            "pasteCopiedFields",
            "Places the cached copied fields on the board",
            array(
                "X-Coordinate (left border)" => "int",
                "Y-Coordinate (top border)" => "int"
            )
        );

        $this->fieldsPlacer = new FieldsPlacer();
    }


    /**
     * Places the cached copied fields on the board.
     *
     * @param int $_posX The X-Position of the top left corner of the template on the board
     * @param int $_posY The Y-Position of the top left corner of the template on the board
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the input coordinates are invalid
     */
    public function pasteCopiedFields(int $_posX, int $_posY): Bool
    {
        $templateFields = $this->parentBoardEditor->copiedFields();
        if ($templateFields == array()) throw new \Exception("The list of cached copied fields is empty.");

        $this->parentBoardEditor->checkCoordinate($_posX, "X", 0, $this->parentBoardEditor->board()->width());
        $this->parentBoardEditor->checkCoordinate($_posY, "Y", 0, $this->parentBoardEditor->board()->height());

        $this->fieldsPlacer->placeTemplate($templateFields, $this->parentBoardEditor->board(), $_posX, $_posY, false);

        // Update selection
        $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();
        $selectionWidth = $selectionCoordinates["B"]["x"] - $selectionCoordinates["A"]["x"];
        $selectionHeight = $selectionCoordinates["B"]["y"] - $selectionCoordinates["A"]["y"];

        $updatedSelectionCoordinates = array(
            "A" => array("x" => $_posX, "y" => $_posY),
            "B" => array("x" => $_posX + $selectionWidth, "y" => $_posY + $selectionHeight)
        );
        $this->parentBoardEditor->setSelectionCoordinates($updatedSelectionCoordinates);

        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
