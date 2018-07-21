<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;

abstract class BaseBorderRenderer
{
    // Attributes
	/**
	 * The list of borders
	 *
	 * @var BaseBorderPart[] $borderParts
	 */
	private $borderParts;

    /**
     * The border symbol grid
     *
     * @var BaseSymbolGrid $borderSymbolGrid
     */
	private $borderSymbolGrid;


	// Magic Methods

	/**
	 * BaseBorderRenderer constructor.
	 */
	public function __construct()
	{
		$this->borderParts = array();
	}


	// Getters and Setters

    /**
     * Returns the rendered borders that were rendered with renderBorderParts().
     *
     * @return mixed The rendered borders
     */
    public function borderSymbolGrid()
    {
        return $this->borderSymbolGrid;
    }


    // Class Methods

	/**
	 * Adds a border to this border symbol grid.
	 *
	 * @param BaseBorderPart $_border The border
	 */
	public function addBorderPart($_border)
	{
		// TODO: Call add inner border?

		foreach ($this->borderParts as $border)
		{
			$border->checkCollisionWith($_border);
		}
		$this->borderParts[] = $_border;
	}

	/**
	 * Resets the list of borders of this border symbol grid to an empty array.
	 */
	public function resetBorders()
	{
		$this->borderParts = array();
	}

	/**
	 * Renders all the border parts that were added with addBorderPart() and adds them to this board symbol grid.
	 */
	abstract public function renderBorderParts();

	/**
	 * Returns whether a specific column contains any border symbols.
	 *
	 * @param int $_x The X-Position of the column
	 *
	 * @return Bool True if the column contains a border symbol, false otherwise
	 */
	private function columnContainsVerticalBorder(int $_x): Bool
	{
		// TODO: Fix this, determine which stuff is outer border
		foreach ($this->borderParts as $border)
		{
			if ($border instanceof VerticalOutputBorderPart)
			{
				if ($border->startsAt()->x() == $_x && $border->endsAt()->x() == $_x) return true;
			}
		}

		return false;
	}
}
