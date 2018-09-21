<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;

/**
 * Stores a list of border parts and provides methods to render the border parts.
 *
 * Use addBorderPart() to add a border part to this border grid
 * Use render() to render all added border parts
 * Use reset() to reset this border grid to an empty border grid
 */
abstract class BaseBorderGrid
{
	// Attributes

	/**
	 * The list of border parts
	 *
	 * @var BaseBorderPart[] $borderParts
	 */
	protected $borderParts;

	/**
	 * The border positions grid
	 *
	 * @param BaseBorderPositionsGrid $borderPositionsGrid
	 */
	protected $borderPositionsGrid;


	// Magic Methods

	/**
	 * BaseBorderGrid constructor.
	 *
	 * @param BaseBorderPositionsGrid $_borderPositionsGrid The border positions grid
	 */
	public function __construct(BaseBorderPositionsGrid $_borderPositionsGrid)
	{
		$this->borderPositionsGrid = $_borderPositionsGrid;
		$this->borderParts = array();
	}


	// Getters and Setters

	/**
	 * Returns the border positions grid.
	 *
	 * @return BaseBorderPositionsGrid The border positions grid
	 */
	public function borderPositionsGrid(): BaseBorderPositionsGrid
	{
		return $this->borderPositionsGrid;
	}


	// Class methods

	/**
	 * Adds a border part to this border grid.
	 *
	 * @param BaseBorderPart $_borderPart The border part
	 */
	public function addBorderPart($_borderPart)
	{
		foreach ($this->borderParts as $borderPart)
		{
			$_borderPart->checkCollisionWith($borderPart);
		}
		$this->borderParts[] = $_borderPart;
	}

	/**
	 * Resets the border grid to an empty border grid.
	 */
	public function reset()
	{
		$this->borderParts = array();
		$this->borderPositionsGrid->reset();
	}

	/**
	 * Renders the border parts that are currently stored in this border grid.
	 *
	 * @param int $_fieldSize The height/width of a field in pixels/symbols/etc
	 *
	 * @return mixed The rendered border grid
	 */
	public function render(int $_fieldSize)
	{
		$renderedBorderParts = $this->renderBorderParts($_fieldSize);
		return $this->renderTotalBorderGrid($renderedBorderParts, $_fieldSize);
	}

	/**
	 * Renders and returns all border parts that are currently stored in this border grid.
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels/symbols/etc
	 *
	 * @return RenderedBorderPart[] The list of rendered border parts
	 */
	protected function renderBorderParts(int $_fieldSize): array
	{
		$renderedBorderParts = array();
		foreach ($this->borderParts as $borderPart)
		{
			$renderedBorderPart = $borderPart->getRenderedBorderPart($_fieldSize);

			$renderedBorderParts[] = $renderedBorderPart;
			$this->borderPositionsGrid->addRenderedBorderPart($renderedBorderPart);
		}

		return $renderedBorderParts;
	}

	/**
	 * Creates and returns a rendered border grid from a list of rendered border parts.
	 *
	 * @param RenderedBorderPart[] $_renderedBorderParts The list of rendered border parts
	 * @param int $_fieldSize The height/width of a field in pixels/symbols/etc
	 *
	 * @return mixed The rendered border grid
	 */
	abstract protected function renderTotalBorderGrid(array $_renderedBorderParts, int $_fieldSize);
}
