<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use GameOfLife\Coordinate;
use GameOfLife\Field;

/**
 * Renders a list of board fields and adds them to a canvas.
 *
 * Call renderBoardFields() to render a list of board fields and add them to a canvas
 */
abstract class BaseBoardFieldRenderer
{
    // Class Methods

    /**
     * Renders a two dimensional list of board fields and adds them to a canvas.
     *
     * @param Field[][] $_boardFields The board fields
     * @param BaseCanvas $_canvas The canvas
     */
    public function renderBoardFields($_boardFields, $_canvas)
    {
        foreach ($_boardFields as $boardFieldRow)
        {
            foreach ($boardFieldRow as $boardField)
            {
                $renderedField = $this->renderBoardField($boardField);
                $renderedFieldPosition = $this->getBoardFieldCanvasPosition($boardField);
                $_canvas->addRenderedBoardFieldAt($renderedField, $renderedFieldPosition);
            }
        }
    }

    /**
     * Renders a board field.
     *
     * @param Field $_field The board field
     *
     * @return mixed The rendered board field
     */
    abstract protected function renderBoardField(Field $_field);

    /**
     * Calculates and returns the position of the board field on the canvas.
     *
     * @param Field $_field The field
     *
     * @return Coordinate The position of the board field on the canvas
     */
    abstract protected function getBoardFieldCanvasPosition(Field $_field): Coordinate;
}
