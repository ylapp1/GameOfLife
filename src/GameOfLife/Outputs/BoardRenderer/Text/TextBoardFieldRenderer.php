<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;

use GameOfLife\Coordinate;
use GameOfLife\Field;
use Output\BoardRenderer\Base\BaseBoardFieldRenderer;

/**
 * Renders a list of board fields as symbols and adds them to a canvas.
 *
 * Call renderBoardFields() to render a list of board fields and add them to a canvas
 */
class TextBoardFieldRenderer extends BaseBoardFieldRenderer
{
    // Attributes

    /**
     * The symbol that is used to print a living cell
     *
     * @var String $cellAliveSymbol
     */
    protected $cellAliveSymbol;

    /**
     * The symbol that is used to print a dead cell
     *
     * @var String $cellDeadSymbol
     */
    protected $cellDeadSymbol;


    // Magic Methods

    /**
     * TextBoardFieldRenderer constructor.
     *
     * @param String $_cellAliveSymbol The symbol that is used to print a living cell
     * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
     */
    public function __construct(String $_cellAliveSymbol, String $_cellDeadSymbol)
    {
        $this->cellAliveSymbol = $_cellAliveSymbol;
        $this->cellDeadSymbol = $_cellDeadSymbol;
    }


    // Class Methods

    /**
     * Calculates and returns the position of the board field on the canvas.
     *
     * @param Field $_field The field
     *
     * @return Coordinate The position of the board field on the canvas
     */
    protected function getBoardFieldCanvasPosition(Field $_field): Coordinate
    {
        return $_field->coordinate();
    }

    /**
     * Renders a board field.
     *
     * @param Field $_field The board field
     *
     * @return String The rendered board field
     */
    protected function renderBoardField(Field $_field)
    {
        if ($_field->isAlive()) return $this->cellAliveSymbol;
        else return $this->cellDeadSymbol;
    }
}
