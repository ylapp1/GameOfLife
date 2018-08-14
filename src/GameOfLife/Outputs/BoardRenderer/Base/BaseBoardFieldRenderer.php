<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use GameOfLife\Coordinate;
use GameOfLife\Field;

/**
 * Renders a list of board fields and adds them to a canvas.
 *
 * Call renderBoardFields() to render a list of board fields and add them to a canvas
 */
abstract class BaseBoardFieldRenderer
{
	// Attributes

	/**
	 * The rendered cell for alive cells
	 *
	 * @var mixed $renderedCellAlive
	 */
	private $renderedCellAlive;

	/**
	 * The rendered cell for dead cells
	 *
	 * @var mixed $renderedCellDead
	 */
	private $renderedCellDead;


	// Magic Methods

	/**
	 * BaseBoardFieldRenderer constructor.
	 *
	 * @param mixed $_renderedCellAlive The rendered cell for alive cells
	 * @param mixed $_renderedCellDead The rendered cell for dead cells
	 */
	public function __construct($_renderedCellAlive = null, $_renderedCellDead = null)
	{
		$this->renderedCellAlive = $_renderedCellAlive;
		$this->renderedCellDead = $_renderedCellDead;
	}


	// Class Methods

    /**
     * Renders a two dimensional list of board fields and returns the list of rendered board fields.
     *
     * @param Field[][] $_boardFields The board fields
     *
     * @return mixed[][] The list of rendered board fields
     */
    public function getRenderedBoardFields($_boardFields): array
    {
    	$renderedBoardFields = array();

        foreach ($_boardFields as $boardFieldRow)
        {
            foreach ($boardFieldRow as $boardField)
            {
                $renderedField = $this->renderBoardField($boardField);
                $renderedFieldPosition = $this->getBoardFieldCanvasPosition($boardField);

                $renderedBoardFields[$renderedFieldPosition->y()][$renderedFieldPosition->x()] = $renderedField;
            }
        }

        return $renderedBoardFields;
    }

    /**
     * Renders a board field.
     *
     * @param Field $_field The board field
     *
     * @return mixed The rendered board field
     */
    protected function renderBoardField(Field $_field)
    {
    	if ($_field->isAlive()) return $this->renderedCellAlive;
    	else return $this->renderedCellDead;
    }

    /**
     * Calculates and returns the position of the board field on the canvas.
     *
     * @param Field $_field The field
     *
     * @return Coordinate The position of the board field on the canvas
     */
    abstract protected function getBoardFieldCanvasPosition(Field $_field): Coordinate;
}
