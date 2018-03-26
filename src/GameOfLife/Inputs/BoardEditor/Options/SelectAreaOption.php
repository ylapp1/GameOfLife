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
        parent::__construct($_parentBoardEditor);

        $this->name = "select";
        $this->aliases = array("selectArea");
        $this->callback = "selectArea";
        $this->description = "Selects an area of fields";
        $this->arguments = array();
    }

    /**
     * Selects an area of fields on the board.
     *
     * @param String $_posXLeft The user inputted posX left
     * @param String $_posYTop The user inputted posY top
     * @param String $_width The user inputted width
     * @param String $_height The user inputted height
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the coordinate is invalid.
     */
    public function selectArea($_posXLeft = null, $_posYTop = null, $_width = null, $_height = null): bool
    {
        $posXLeft = $this->getPosXLeft($_posXLeft);
        $posXRight = $this->getPosXRight($posXLeft, $_width);
        $posYTop = $this->getPosYTop($_posYTop);
        $posYBottom = $this->getPosYBottom($posYTop, $_height);

        $this->parentBoardEditor->selectArea($posXLeft, $posYTop, $posXRight, $posYBottom);
        $this->parentBoardEditor->outputBoard();

        return false;
    }

    /**
     * Returns the X-Position of the left border of the selection.
     *
     * @param String $_posXLeft The user inputted value
     *
     * @return int The X-Position of the left border of the selection
     *
     * @throws \Exception The exception when the value is invalid
     */
    private function getPosXLeft($_posXLeft = null)
    {
        if ($_posXLeft)
        {
            $posXLeft = (int)$_posXLeft;

            $this->parentBoardEditor->checkCoordinate(
                $posXLeft,
                "X",
                0,
                $this->parentBoardEditor->board()->width()
            );
        }
        else
        {
            $posXLeft = $this->parentBoardEditor->readCoordinate(
                "X",
                "top left border of the selection",
                0,
                $this->parentBoardEditor->board()->width()
            );
        }

        return $posXLeft;
    }

    /**
     * Returns the X-Position of the right border of the selection.
     *
     * @param int $_posXLeft The X-Position of the left border of the selection
     * @param String $_width The user inputted value
     *
     * @return int The X-Position of the right border of the selection
     *
     * @throws \Exception The exception when the value is invalid
     */
    private function getPosXRight($_posXLeft, $_width = null)
    {
        if ($_width) $width = (int)$_width;
        else
        {
            echo "Width of the selection: ";
            $userInput = $this->parentBoardEditor->readInput("php://stdin");

            if (is_numeric($userInput)) $width = (int)$userInput;
            else throw new \Exception("The entered value is not numeric.");
        }

        if ($width == 0) throw new \Exception("The width of the selection may not be 0.");

        $posXRight = $_posXLeft + $width;
        $this->parentBoardEditor->checkCoordinate(
            $posXRight,
            "X",
            0,
            $this->parentBoardEditor->board()->width()
        );

        return $posXRight;
    }

    /**
     * Returns the Y-Position of the top border of the selection.
     *
     * @param String $_posYTop The user inputted value
     *
     * @return int The Y-Position of the top border of the selection
     *
     * @throws \Exception The exception when the value is invalid
     */
    private function getPosYTop($_posYTop = null)
    {
        if ($_posYTop)
        {
            $posYTop = (int)$_posYTop;

            $this->parentBoardEditor->checkCoordinate(
                $posYTop,
                "Y",
                0,
                $this->parentBoardEditor->board()->height()
            );
        }
        else
        {
            $posYTop = $this->parentBoardEditor->readCoordinate(
                "Y",
                "top left border of the selection",
                0,
                $this->parentBoardEditor->board()->height()
            );
        }

        return $posYTop;
    }

    /**
     * Returns the Y-Position of the bottom border of the selection.
     *
     * @param int $_posYTop The Y-Position of the top border of the selection
     * @param String $_height The user inputted value
     *
     * @return int The Y-Position of the bottom border of the selection
     *
     * @throws \Exception The exception when the value is invalid
     */
    private function getPosYBottom($_posYTop, $_height = null)
    {
        if ($_height) $height = (int)$_height;
        else
        {
            echo "Height of the selection: ";
            $userInput = $this->parentBoardEditor->readInput("php://stdin");

            if (is_numeric($userInput)) $height = (int)$userInput;
            else throw new \Exception("The entered value is not numeric.");
        }

        if ($height == 0) throw new \Exception("The height of the selection may not be 0.");

        $posYBottom = $_posYTop + $height;
        $this->parentBoardEditor->checkCoordinate(
            $posYBottom,
            "Y",
            0,
            $this->parentBoardEditor->board()->height()
        );

        return $posYBottom;
    }
}
