<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Board;

/**
 * Places a template on a board.
 */
class TemplatePlacer
{
    /**
     * Places a template on a board.
     *
     * @param Template $_template The template that shall be placed
     * @param Board $_board The board on which the template will be placed
     * @param int $_posX X-Position of the top left corner of the template on the board
     * @param int $_posY Y-Position of the top left corner of the template on the board
     * @param bool $_adjustDimensions Indicates whether the board shall be adjusted to match the template width and height
     *
     * @return bool True: No error while placing template
     *              False: Error while placing template
     */
    public function placeTemplate(Template $_template, Board $_board, int $_posX, int $_posY, bool $_adjustDimensions): bool
    {
        if ($_adjustDimensions)
        {
            $_board->setWidth($_template->width());
            $_board->setHeight($_template->height());
            $_board->setFields($_template->fields());

            return true;
        }
        else
        {
            if ($this->isTemplateOutOfBounds($_board, $_template, $_posX, $_posY)) return false;
            else
            {
                for ($y = 0; $y < $_template->height(); $y++)
                {
                    for ($x = 0; $x < $_template->width(); $x++)
                    {
                        if ($_template->getField($x, $y)->isAlive())
                        {
                            $_board->setField($x + $_posX, $y + $_posY, true);
                        }
                    }
                }

                return true;
            }
        }
    }

    /**
     * Checks whether a template is out of bounds.
     *
     * @param Board $_board The board on which the template shall be placed
     * @param Template $_template The template
     * @param int $_posX X-Coordinate of the top left border of the template
     * @param int $_posY Y-Coordinate of the top left border of the template
     *
     * @return bool True: Template is out of bounds
     *              False: Template is not out of bounds
     */
    private function isTemplateOutOfBounds(Board $_board, Template $_template, int $_posX, int $_posY): bool
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
