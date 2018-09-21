<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use Simulator\Board;
use Simulator\Field;

/**
 * Places template fields on a board.
 */
class FieldsPlacer
{
    // Class Methods

    /**
     * Places template fields on a board.
     *
     * @param Field[][] $_templateFields The template fields
     * @param Board $_board The board
     * @param int $_posX The X-coordinate of the top left corner of the template fields on the board
     * @param int $_posY The Y-coordinate of the top left corner of the template fields on the board
     * @param Bool $_adjustDimensions Indicates whether the board shall be adjusted to contain only the template fields
     *
     * @throws \Exception The exception when the template fields exceed a border of the board
     */
    public function placeTemplateFields(array $_templateFields, Board $_board, int $_posX, int $_posY, Bool $_adjustDimensions)
    {
        $templateHeight = count($_templateFields);
        $templateWidth = count($_templateFields[0]);

        if ($_adjustDimensions)
        {
            $_board->setWidth($templateWidth);
            $_board->setHeight($templateHeight);
            $fields = array();

            foreach ($_templateFields as $y => $rowFields)
            {
                $fields[$y] = array();
                foreach ($rowFields as $x => $rowField)
                {
                    $field = clone $rowField;
                    $field->setParentBoard($_board);
                    $fields[$y][$x] = $field;
                }
            }

            $_board->setFields($fields);
        }
        else
        {
            $exceededBorder = $this->isTemplateOutOfBounds($_board, $templateWidth, $templateHeight, $_posX, $_posY);
            if ($exceededBorder)
            {
                throw new \Exception("The template exceeds the " . $exceededBorder . " border of the board.");
            }
            else
            {
                foreach ($_templateFields as $rowFields)
                {
                    foreach ($rowFields as $rowField)
                    {
                        $_board->setFieldState($rowField->coordinate()->x() + $_posX, $rowField->coordinate()->y() + $_posY, $rowField->isAlive());
                    }
                }
            }
        }
    }

    /**
     * Checks whether template fields exceed a border of the target board.
     *
     * @param Board $_board The board on which the template fields will be placed
     * @param int $_templateWidth The number of fields per row
     * @param int $_templateHeight The number of rows
     * @param int $_posX The X-coordinate of the top left corner of the template fields on the board
     * @param int $_posY The Y-coordinate of the top left corner of the template fields on the board
     *
     * @return String|null The name of the border that is exceeded or null if the template fields exceed no border
     */
    public function isTemplateOutOfBounds(Board $_board, int $_templateWidth, int $_templateHeight, int $_posX, int $_posY)
    {
        if ($_posX < 0) return "left";
        if ($_posY < 0) return "top";
        if ($_posX + $_templateWidth > $_board->width()) return "right";
        if ($_posY + $_templateHeight > $_board->height()) return "bottom";
        else return null;
    }
}
