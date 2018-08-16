<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Image\Border\ImageBorder;
use GameOfLife\Board;

/**
 * Renders a border and its inner borders and adds them to a canvas.
 */
abstract class BaseBorderRenderer
{
	// Attributes

	/**
	 * The rendered border grid
	 *
	 * @var BaseBorderGrid $renderedBorderGrid
	 */
	protected $borderGrid;

	/**
	 * The main border
	 *
	 * @var ImageBorder $border
	 */
	protected $border;

	/**
	 * The list of border parts
	 *
	 * @var BorderPart $borderParts
	 */
	protected $borderParts;


	// Magic Methods

	/**
	 * BaseBorderRenderer constructor.
	 *
	 * @param Board $_board The board
	 * @param BaseBorder $_border The main border
	 * @param BaseBorderGrid $_borderGrid The border grid
	 * @param Bool $_hasBackgroundGrid If true, the border grid will have a background grid
	 */
	public function __construct(Board $_board, $_border, $_borderGrid, Bool $_hasBackgroundGrid)
	{
		$this->border = $_border;
		$this->borderParts = array();
		$this->borderGrid = $_borderGrid;
	}


	// Class Methods

	/**
	 * Adds all border parts to the border grid and returns the updated border grid.
	 *
	 * @return BaseBorderGrid The border grid
	 */
	public function getBorderGrid()
	{
		$this->borderGrid->reset();

		/** @var BorderPart[] $borderParts */
		$borderParts = array_merge($this->borderParts, $this->border->getBorderParts());

		$processedBorderParts = array();

		foreach ($borderParts as $borderPart)
		{
			foreach ($processedBorderParts as $processedBorderPart)
			{
				$borderPart->checkCollisionWith($processedBorderPart);
			}
			$processedBorderParts[] = $borderPart;

			$this->borderGrid->addBorderPart($borderPart);
		}

		return $this->borderGrid;
	}
}
