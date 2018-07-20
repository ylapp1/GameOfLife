<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;


abstract class BaseBorderRenderer
{
	/**
	 * The list of borders
	 *
	 * @var BaseBorderPart[] $borders
	 */
	private $borders;


	/**
	 * BaseBorderRenderer constructor.
	 */
	public function __construct()
	{
		$this->borders = array();
	}


	/**
	 * Adds a border to this border symbol grid.
	 *
	 * @param BaseBorderPart $_border The border
	 */
	public function addBorderPart($_border)
	{
		// TODO: Call add inner border?

		foreach ($this->borders as $border)
		{
			$border->collideWith($_border);
		}
		$this->borders[] = $_border;
	}

	/**
	 * Resets the list of borders of this border symbol grid to an empty array.
	 */
	public function resetBorders()
	{
		$this->borders = array();
	}

	/**
	 * Renders all the border parts that were added with addBorderPart().
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
		foreach ($this->borders as $border)
		{
			if ($border instanceof VerticalOutputBorderPart)
			{
				if ($border->startsAt()->x() == $_x && $border->endsAt()->x() == $_x) return true;
			}
		}

		return false;
	}
}
