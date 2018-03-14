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
class TemplatePlacer
{
    /**
     * Places a template on a board.
     *
     * @param Template $_template The template that will be placed on the board
     * @param Board $_board The board on which the template will be placed
     * @param int $_posX The X-coordinate of the top left corner of the template on the board
     * @param int $_posY The Y-coordinate of the top left corner of the template on the board
     * @param Bool $_adjustDimensions Indicates whether the board shall be adjusted to match the template width and height
     *
     * @return Bool True: No error while placing the template
     *              False: Error while placing the template
     */
    public function placeTemplate(Template $_template, Board $_board, int $_posX, int $_posY, Bool $_adjustDimensions): Bool
    {
        if ($_adjustDimensions)
        {
            $_board->setWidth($_template->width());
            $_board->setHeight($_template->height());

            foreach ($_template->fields() as $row)
            {
                /** @var Field $field */
                foreach ($row as $field)
                {
                    $field->setParentBoard($_board);
                }
            }

            $_board->setFields($_template->fields());

            return true;
        }
        else
        {
            if ($this->isTemplateOutOfBounds($_board, $_template, $_posX, $_posY)) return false;
            else
            {
                foreach ($_template->fields() as $row)
                {
                    /** @var Field $field */
                    foreach ($row as $field)
                    {
                        $_board->setField($field->x() + $_posX, $field->y() + $_posY, $field->isAlive());
                    }
                }

                return true;
            }
        }
    }

    /**
     * Checks whether a template is out of bounds.
     *
     * @param Board $_board The board on which the template will be placed
     * @param Template $_template The template
     * @param int $_posX The X-coordinate of the top left border of the template
     * @param int $_posY The Y-coordinate of the top left border of the template
     *
     * @return Bool True: The template is out of bounds
     *              False: The template is not out of bounds
     */
    public function isTemplateOutOfBounds(Board $_board, Template $_template, int $_posX, int $_posY): Bool
    {
        if ($_posX < 0 ||
            $_posY < 0 ||
            $_posX + $_template->width() > $_board->width() ||
            $_posY + $_template->height() > $_board->height())
        {
            return true;
        }
        else return false;
    }
}
