<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use GameOfLife\Field;

/**
 * Renders a list of board fields.
 *
 * Call getRenderedBoardFields() to render a list of board fields
 */
abstract class BaseBoardFieldRenderer
{
	// Attributes

	/**
	 * The image/symbol/etc that will be used to render alive cells
	 *
	 * @var mixed $renderedCellAlive
	 */
	private $renderedCellAlive;

	/**
	 * The image/symbol/etc that will be used to render dead cells
	 *
	 * @var mixed $renderedCellDead
	 */
	private $renderedCellDead;


	// Magic Methods

	/**
	 * BaseBoardFieldRenderer constructor.
	 *
	 * @param mixed $_renderedCellAlive The image/symbol/etc that will be used to render alive cells
	 * @param mixed $_renderedCellDead The image/symbol/etc that will be used to render dead cells
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
	            if ($boardField->isAlive()) $renderedField = $this->renderedCellAlive;
	            else $renderedField = $this->renderedCellDead;

                if ($renderedField)
                {
	                $renderedBoardFields[$boardField->coordinate()->y()][$boardField->coordinate()->x()] = $renderedField;
                }
            }
        }

        return $renderedBoardFields;
    }
}
