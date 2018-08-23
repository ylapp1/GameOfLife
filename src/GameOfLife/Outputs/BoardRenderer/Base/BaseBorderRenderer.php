<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use BoardRenderer\Base\Border\BaseBorder;

/**
 * Fills and returns a border grid.
 */
abstract class BaseBorderRenderer
{
	// Attributes

	/**
	 * The border grid
	 *
	 * @var BaseBorderGrid $borderGrid
	 */
	protected $borderGrid;

	/**
	 * The border
	 *
	 * @var BaseBorder $border
	 */
	protected $border;


	// Magic Methods

	/**
	 * BaseBorderRenderer constructor.
	 *
	 * @param BaseBorder $_mainBorder The main border
	 * @param BaseBorderGrid $_borderGrid The border grid
	 * @param Bool $_hasBackgroundGrid If true the border grid will contain a background grid
	 */
	public function __construct($_mainBorder, $_borderGrid, Bool $_hasBackgroundGrid)
	{
		$this->border = $_mainBorder;
		$this->borderGrid = $_borderGrid;

		if ($_hasBackgroundGrid) $this->addBackgroundBorderGrid($_mainBorder);
	}

	/**
	 * Adds a background grid to a border.
	 *
	 * @param BaseBorder $_parentBorder The parent border of the background grid
	 */
	abstract protected function addBackgroundBorderGrid($_parentBorder);


	// Class Methods

	/**
	 * Adds all border parts to the border grid and returns the filled border grid.
	 *
	 * @return BaseBorderGrid The filled border grid
	 */
	public function getBorderGrid()
	{
		$this->borderGrid->reset();

		foreach ($this->border->getBorderParts() as $borderPart)
		{
			$this->borderGrid->addBorderPart($borderPart);
		}

		return $this->borderGrid;
	}
}
