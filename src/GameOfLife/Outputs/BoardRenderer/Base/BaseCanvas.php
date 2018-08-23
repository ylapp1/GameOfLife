<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

/**
 * Stores and combines the rendered border grid and the rendered board fields.
 */
abstract class BaseCanvas
{
	// Attributes

	/**
	 * The border grid
	 *
	 * @var BaseBorderGrid $borderGrid
	 */
	protected $borderGrid;

	/**
	 * The list of rendered board fields
	 *
	 * @var mixed[][] $renderedBoardFields
	 */
	protected $renderedBoardFields;

	/**
	 * Indicates whether this canvas caches the border grid
	 *
	 * @var Bool $cachesBorderGrid
	 */
	protected $cachesBorderGrid;

	/**
	 * The cached rendered border grid
	 *
	 * @var mixed $cachedRenderedBorderGrid
	 */
	protected $cachedRenderedBorderGrid;

	/**
	 * The field size of the currently cached rendered border grid
	 *
	 * @var int $cachedRenderedBorderGridFieldSize
	 */
	protected $cachedRenderedBorderGridFieldSize;


	// Magic Methods

	/**
	 * BaseCanvas constructor.
	 *
	 * @param Bool $_cachesBorderGrid Indicates whether this canvas caches the border grid
	 */
	public function __construct(Bool $_cachesBorderGrid = true)
	{
		$this->cachesBorderGrid = $_cachesBorderGrid;
	}


	// Getters and Setters

	/**
	 * Sets the border grid of the canvas.
	 *
	 * @param BaseBorderGrid $_borderGrid The border grid
	 */
	public function setBorderGrid($_borderGrid)
	{
		$this->borderGrid = $_borderGrid;
	}

	/**
	 * Sets the rendered board fields of the canvas.
	 *
	 * @param mixed[][] $_renderedBoardFields The list of rendered board fields
	 */
	public function setRenderedBoardFields(array $_renderedBoardFields)
	{
		$this->renderedBoardFields = $_renderedBoardFields;
	}


	// Class Methods

	/**
	 * Returns whether this canvas has a cached border grid.
	 *
	 * @return Bool True if the canvas has a cached border grid, false otherwise
	 */
	public function hasCachedBorderGrid(): Bool
	{
		if ($this->cachesBorderGrid && $this->borderGrid) return true;
		else return false;
	}

	/**
	 * Returns the rendered border grid for a specific field size.
	 *
	 * @param int $_fieldSize The height/width of a field in pixels/symbols/etc
	 *
	 * @return mixed The rendered border grid
	 */
	protected function getRenderedBorderGrid(int $_fieldSize)
	{
		if (! $this->cachesBorderGrid ||
			! $this->cachedRenderedBorderGrid ||
			! ($this->cachedRenderedBorderGridFieldSize && $this->cachedRenderedBorderGridFieldSize == $_fieldSize))
		{
			$this->cachedRenderedBorderGrid = $this->borderGrid->renderBorderGrid($_fieldSize);
			$this->cachedRenderedBorderGridFieldSize = $_fieldSize;
		}

		return $this->cachedRenderedBorderGrid;
	}

	/**
	 * Renders the total board (combines board fields and border grid).
	 * This method must be called after setBorderGrid() and setRenderedBoardFields() were called
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels/symbols/etc
	 *
	 * @return mixed The total rendered board
	 */
    abstract public function render(int $_fieldSize);
}
