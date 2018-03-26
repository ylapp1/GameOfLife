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
        parent::__construct($_parentBoardEditor);

        $this->name = "paste";
        $this->aliases = array("pastedCopiedFields");
        $this->callback = "pasteCopiedFields";
        $this->description = "Places the cached copied fields on the board";
        $this->arguments = array();

        $this->fieldsPlacer = new FieldsPlacer();
    }


    /**
     * Places the cached copied fields on the board.
     *
     * @param String $_posX The X-Position of the top left corner of the template on the board
     * @param String $_posY The Y-Position of the top left corner of the template on the board
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the input coordinates are invalid
     */
    public function pasteCopiedFields($_posX = null, $_posY = null): Bool
    {
        $templateFields = $this->parentBoardEditor->copiedFields();
        if ($templateFields == array()) throw new \Exception("The list of cached copied fields is empty.");

        if ($_posX) $posX = $_posX;
        else
        {
            $posX = $this->parentBoardEditor->readCoordinate(
                "X",
                "top left border of the copied fields on the board",
                0,
                $this->parentBoardEditor->board()->width()
            );
        }

        if ($_posY) $posY = $_posY;
        else
        {
            $posY = $this->parentBoardEditor->readCoordinate(
                "Y",
                "top left border of the copied fields on the board",
                0,
                $this->parentBoardEditor->board()->height()
            );
        }

        $this->fieldsPlacer->placeTemplate($templateFields, $this->parentBoardEditor->board(), $posX, $posY, false);

        // Update selection
        $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();
        $selectionWidth = $selectionCoordinates["B"]["x"] - $selectionCoordinates["A"]["x"];
        $selectionHeight = $selectionCoordinates["B"]["y"] - $selectionCoordinates["A"]["y"];

        $updatedSelectionCoordinates = array(
            "A" => array("x" => $posX, "y" => $posY),
            "B" => array("x" => $posX + $selectionWidth, "y" => $posY + $selectionHeight)
        );
        $this->parentBoardEditor->setSelectionCoordinates($updatedSelectionCoordinates);

        $this->parentBoardEditor->outputBoard();
        return false;
    }
}
