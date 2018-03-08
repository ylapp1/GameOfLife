<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;

/**
 * Toggles a field on the board.
 */
class ToggleFieldOption extends BoardEditorOption
{
    /**
     * ToggleFieldOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "toggle";
        $this->callback = "toggleField";
        $this->description = "Toggles a field";
        $this->numberOfArguments = 2;
    }

    /**
     * Sets a field on the board and displays the updated board or displays an error in case of invalid coordinates.
     *
     * @param int $_x X-Coordinate of the field
     * @param int $_y Y-Coordinate of the field
     *
     * @return bool Indicates whether the board editor session is finished
     */
    public function toggleField($_x, $_y)
    {
        $x = $this->getIntegerCoordinate($_x, "x", 0, $this->parentBoardEditor->board()->width() - 1);
        $y = $this->getIntegerCoordinate($_y, "y", 0, $this->parentBoardEditor->board()->height() - 1);

        if ($x === false || $y === false) return false;

        $currentCellState = $this->parentBoardEditor->board()->getFieldStatus($x, $y);
        $this->parentBoardEditor->board()->setField($x, $y, !$currentCellState);
        $this->parentBoardEditor->output()->outputBoard($this->parentBoardEditor->board(), $x, $y);

        return false;
    }

    /**
     * Checks whether a coordinate is between a minimum and a maximum value.
     *
     * @param String $_inputCoordinate The input coordinate
     * @param String $_coordinateName Name of the coordinate ("x", "y")
     * @param int $_minValue The minimum value of the coordinate
     * @param int $_maxValue The maximum value of the coordinate
     *
     * @return bool|int False or the integer coordinate
     */
    private function getIntegerCoordinate(String $_inputCoordinate, String $_coordinateName, int $_minValue, int $_maxValue)
    {
        $coordinate = (int)$_inputCoordinate;

        if ($coordinate < $_minValue || $coordinate > $_maxValue)
        {
            echo "Error: Invalid value for " . $_coordinateName . " specified (Value must be between " . $_minValue . " and " . $_maxValue . ")\n";
            return false;
        }
        else return $coordinate;
    }
}