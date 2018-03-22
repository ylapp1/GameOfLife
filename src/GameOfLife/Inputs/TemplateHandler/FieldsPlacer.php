<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Board;
use GameOfLife\Field;

/**
 * Places a template on a board.
 */
class FieldsPlacer
{
    /**
     * Places a template on a board.
     *
     * @param array $_templateFields The template that will be placed on the board
     * @param Board $_board The board on which the template will be placed
     * @param int $_posX The X-coordinate of the top left corner of the template on the board
     * @param int $_posY The Y-coordinate of the top left corner of the template on the board
     * @param Bool $_adjustDimensions Indicates whether the board shall be adjusted to match the template width and height
     *
     * @throws \Exception The exception when the template exceeds a border of the board
     */
    public function placeTemplate(array $_templateFields, Board $_board, int $_posX, int $_posY, Bool $_adjustDimensions)
    {
        $templateHeight = count($_templateFields);
        $templateWidth = count($_templateFields[0]);

        if ($_adjustDimensions)
        {
            $_board->setWidth($templateWidth);
            $_board->setHeight($templateHeight);

            foreach ($_templateFields as $row)
            {
                /** @var Field $field */
                foreach ($row as $field)
                {
                    $field->setParentBoard($_board);
                }
            }

            $_board->setFields($_templateFields);
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
                foreach ($_templateFields as $row)
                {
                    /** @var Field $field */
                    foreach ($row as $field)
                    {
                        $_board->setField($field->x() + $_posX, $field->y() + $_posY, $field->isAlive());
                    }
                }
            }
        }
    }

    /**
     * Checks whether a template is out of bounds.
     *
     * @param Board $_board The board on which the template will be placed
     * @param int $_templateWidth The template width
     * @param int $_templateHeight The template height
     * @param int $_posX The X-coordinate of the top left border of the template
     * @param int $_posY The Y-coordinate of the top left border of the template
     *
     * @return String|Bool The name of the border that is exceeded or false if the template is not out of bounds
     */
    public function isTemplateOutOfBounds(Board $_board, int $_templateWidth, int $_templateHeight, int $_posX, int $_posY)
    {
        if ($_posX < 0) return "left";
        if ($_posY < 0) return "top";
        if ($_posX + $_templateWidth > $_board->width()) return "right";
        if ($_posY + $_templateHeight > $_board->height()) return "bottom";
        else return false;
    }
}
