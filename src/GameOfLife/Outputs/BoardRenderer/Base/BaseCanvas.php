<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

/**
 * Canvas on which borders and cells can be drawn.
 */
abstract class BaseCanvas
{
	// Attributes

	/**
	 * The border grid that was created by the border renderer
	 *
	 * @var BaseBorderGrid $borderGrid
	 */
	protected $borderGrid;

	/**
	 * The cached rendered border grid
	 *
	 * @var mixed $cachedRenderedBorderGrid
	 */
	protected $cachedRenderedBorderGrid;

	/**
	 * Indicates whether this canvas caches the border grid
	 *
	 * @var Bool $cachesBorderGrid
	 */
	protected $cachesBorderGrid;


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


	// Class Methods

	/**
	 * Returns whether this canvas caches the border grid.
	 *
	 * @return Bool Indicates whether this canvas caches the border grid
	 */
	public function hasCachedBorderGrid(): Bool
	{
		if ($this->cachesBorderGrid && $this->cachedRenderedBorderGrid) return true;
		else return false;
	}

    /**
     * Resets the content of the canvas.
     */
    abstract public function reset();

    /**
     * Adds the rendered border grid to the canvas.
     *
     * @param BaseBorderGrid $_borderGrid The border grid
     * @param int $_fieldSize The height/width of a single field in pixels/symbols/etc
     */
    public function addBorderGrid($_borderGrid, int $_fieldSize)
    {
	    if (! $this->cachedRenderedBorderGrid || ! $this->cachesBorderGrid)
	    {
		    $this->borderGrid = $_borderGrid;
		    $this->cachedRenderedBorderGrid = $_borderGrid->renderBorderGrid($_fieldSize);
	    }
    }

    /**
     * Adds the rendered board fields to the canvas.
     *
     * @param mixed[][] $_renderedBoardFields The list of rendered board fields
     * @param int $_fieldSize The height/width of a single field in pixels/symbols/etc
     */
    abstract public function addRenderedBoardFields(array $_renderedBoardFields, int $_fieldSize);

    /**
     * Returns the content of the canvas.
     *
     * @return mixed The content of the canvas
     */
    abstract public function getContent();
}
