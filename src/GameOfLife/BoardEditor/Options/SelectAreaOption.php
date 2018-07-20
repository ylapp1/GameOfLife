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

/**
 * Selects an area of fields on the board.
 */
class SelectAreaOption extends BoardEditorOption
{
    /**
     * SelectAreaOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct(
            $_parentBoardEditor,
            "select",
            array("selectArea"),
            "selectArea",
            "Selects an area of fields",
            array(
                "X-Coordinate (left border)" => "int",
                "Width" => "int",
                "Y-Coordinate (top border)" => "int",
                "Height" => "int"
            )
        );
    }

    /**
     * Selects an area of fields on the board.
     *
     * @param int $_posXLeft The user inputted posX left
     * @param int $_width The user inputted width
     * @param int $_posYTop The user inputted posY top
     * @param int $_height The user inputted height
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the coordinate is invalid.
     */
    public function selectArea(int $_posXLeft, int $_width, int $_posYTop, int $_height): bool
    {
        $this->parentBoardEditor->checkCoordinate($_posXLeft, "X", 0, $this->parentBoardEditor->board()->width() - 1);
        $this->parentBoardEditor->checkCoordinate($_posYTop, "Y", 0, $this->parentBoardEditor->board()->height() - 1);

        if ($_width == 0) throw new \Exception("The width of the selection may not be 0.");
        $posXRight = $_posXLeft + $_width;
        $this->parentBoardEditor->checkCoordinate($posXRight, "X", 0, $this->parentBoardEditor->board()->width() - 1);

        if ($_height == 0) throw new \Exception("The height of the selection may not be 0.");
        $posYBottom = $_posYTop + $_height;
        $this->parentBoardEditor->checkCoordinate($posYBottom, "Y", 0, $this->parentBoardEditor->board()->height() - 1);

        $this->parentBoardEditor->selectArea($_posXLeft, $_posYTop, $posXRight, $posYBottom);
        $this->parentBoardEditor->outputBoard();

        return false;
    }
}
