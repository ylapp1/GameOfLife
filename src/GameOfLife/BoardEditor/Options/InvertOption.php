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
 * Inverts the current selection or the whole board.
 */
class InvertOption extends BoardEditorOption
{
    /**
     * InvertOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "invert",
            array(),
            "invert",
            "Inverts the current selection or the whole board"
        );
    }

    /**
     * Inverts the current selection or the whole board.
     *
     * @return bool Indicates whether the board editing is finished
     */
    public function invert()
    {
        $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();

        if ($selectionCoordinates != array()) $this->invertSelection($selectionCoordinates);
        else $this->parentBoardEditor->board()->invertFields();

        $this->parentBoardEditor->outputBoard();

        return false;
    }

    /**
     * Inverts the fields inside the selection.
     *
     * @param array $_selectionCoordinates The selection coordinates
     */
    private function invertSelection(array $_selectionCoordinates)
    {
        for ($y = $_selectionCoordinates["A"]["y"]; $y <= $_selectionCoordinates["B"]["y"]; $y++)
        {
            for ($x = $_selectionCoordinates["A"]["x"]; $x <= $_selectionCoordinates["B"]["x"]; $x++)
            {
                /** @var Field $field */
                $field = $this->parentBoardEditor->board()->fields()[$y][$x];
                $field->setValue(! $field->value());
            }
        }
    }
}
