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
use GameOfLife\Field;

/**
 * Copies the fields in the current selection area.
 */
class CopySelectionOption extends BoardEditorOption
{
    /**
     * ExitOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "copy";
        $this->aliases = array("copySelection");
        $this->callback = "copySelection";
        $this->description = "Copies the fields in the current selection area";
        $this->arguments = array();
    }

    /**
     * Copies the fields in the current selection area.
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the selection coordinates are empty
     */
    public function copySelection(): Bool
    {
        $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();
        if ($selectionCoordinates == array()) throw new \Exception("There are no fields selected at the moment.");

        $copiedFields = array();

        for ($y = $selectionCoordinates["A"]["y"]; $y <= $selectionCoordinates["B"]["y"]; $y++)
        {
            $copiedFields[$y] = array();

            for ($x = $selectionCoordinates["A"]["x"]; $x <= $selectionCoordinates["B"]["x"]; $x++)
            {
                /** @var Field $field */
                $field = clone $this->parentBoardEditor->board()->fields()[$y][$x];
                $field->setX($x - $selectionCoordinates["A"]["x"]);
                $field->setY($y - $selectionCoordinates["A"]["y"]);

                $copiedFields[$y][$x] = $field;
            }
        }

        $this->parentBoardEditor->setCopiedFields($copiedFields);
        echo "Fields successfully copied.\n";

        return false;
    }
}
