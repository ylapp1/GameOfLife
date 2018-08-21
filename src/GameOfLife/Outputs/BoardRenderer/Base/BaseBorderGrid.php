<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;
use GameOfLife\Board;

/**
 * Stores the rendered borders and the rendered background grid.
 */
abstract class BaseBorderGrid
{
	// Attributes

	/**
	 * The list of border parts
	 *
	 * @var BorderPart[] $borderParts
	 */
	protected $borderParts;

	/**
	 * The rendered border parts
	 *
	 * @var RenderedBorderPart[] $renderedBorderParts
	 */
	protected $renderedBorderParts;

	/**
	 * The grid of border positions
	 * The fields in this array are positioned "left to the corresponding cell"
	 *
	 * @var BorderPartThickness[][] $borderPositionsGrid
	 */
	protected $borderPositionsGrid;

	/**
	 * The board for which the border grid is created
	 *
	 * @var Board $board
	 */
	protected $board;


	// Magic Methods

	/**
	 * BaseBorderGrid constructor.
	 *
	 * @param Board $_board The board for which the border grid is created
	 */
	public function __construct(Board $_board)
	{
		$this->board = $_board;
		$this->borderParts = array();
		$this->borderPositionsGrid = array();
		$this->renderedBorderParts = array();
	}


	// Class methods

	/**
	 * Adds a border part to this border grid.
	 *
	 * @param BorderPart $_borderPart The border part
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
	 * Adds a list of border part grid positions to the border positions grid.
	 *
	 * @param RenderedBorderPart $_renderedBorderPart The rendered border part
	 */
	protected function updateBorderPositionsGrid($_renderedBorderPart)
	{
		foreach ($_renderedBorderPart->borderPartGridPositions() as $at)
		{
			if (! isset($this->borderPositionsGrid[$at->y()]))
			{
				$this->borderPositionsGrid[$at->y()] = array();
			}

			$borderThickness = $_renderedBorderPart->parentBorderPart()->thickness();

			if (isset($this->borderPositionsGrid[$at->y()][$at->x()]))
			{
				$borderPositionThickness = $this->borderPositionsGrid[$at->y()][$at->x()];

				if ($borderPositionThickness->width() < $borderThickness->width())
				{
					$borderPositionThickness->setWidth($borderThickness->width());
				}
				if ($borderPositionThickness->height() < $borderThickness->height())
				{
					$borderPositionThickness->setHeight($borderPositionThickness->height());
				}
			}
			else $this->borderPositionsGrid[$at->y()][$at->x()] = clone $borderThickness;
		}
	}

	/**
	 * Creates and returns a rendered border grid from the currently added rendered border parts.
	 *
	 * @param int $_fieldSize The field size in pixels/symbols/etc
	 *
	 * @return mixed The rendered border grid
	 */
	abstract public function renderBorderGrid(int $_fieldSize);

	/**
	 * Renders all border parts.
	 * Must be called in renderBorderGrid() implementations.
	 *
	 * @param int $_fieldSize The height/width of a single field in pixels/symbols/etc
	 */
	protected function renderBorderParts(int $_fieldSize)
	{
		if (! $this->renderedBorderParts)
		{
			foreach ($this->borderParts as $borderPart)
			{
				$renderedBorderPart = $borderPart->getRenderedBorderPart($_fieldSize);

				$this->renderedBorderParts[] = $renderedBorderPart;
				$this->updateBorderPositionsGrid($renderedBorderPart);
			}
		}
	}

	/**
	 * Resets the border grid.
	 */
	public function reset()
	{
		$this->borderPositionsGrid = array();
		$this->renderedBorderParts = array();
	}

	/**
	 * Returns the maximum border height in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The maximum border height in that row
	 */
	public function getMaximumBorderHeightInRow(int $_y): int
	{
		$maximumBorderHeight = 0;

		if (isset($this->borderPositionsGrid[$_y]))
		{
			foreach ($this->borderPositionsGrid[$_y] as $x => $borderHeight)
			{
				if ($borderHeight->height() > $maximumBorderHeight) $maximumBorderHeight = $borderHeight->height();
			}
		}

		return $maximumBorderHeight;
	}

	/**
	 * Returns the maximum border width in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The maximum border with in that column
	 */
	public function getMaximumBorderWidthInColumn(int $_x): int
	{
		$maximumBorderWidth = 0;

		foreach ($this->borderPositionsGrid as $y => $borderPositionsRow)
		{
			if (isset($borderPositionsRow[$_x]))
			{
				$borderWidth = $borderPositionsRow[$_x];
				if ($borderWidth->width() > $maximumBorderWidth) $maximumBorderWidth = $borderWidth->width();
			}
		}

		return $maximumBorderWidth;
	}

	/**
	 * Returns the total border width until a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The total border width
	 */
	public function getTotalBorderWidthUntilColumn(int $_x): int
	{
		$totalBorderWidth = 0;

		foreach ($this->getSortedBorderColumnIds() as $x)
		{
			if ($x > $_x) break;
			$totalBorderWidth += $this->getMaximumBorderWidthInColumn($x);
		}

		return $totalBorderWidth;
	}

	/**
	 * Returns the total border height until a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The total border height
	 */
	public function getTotalBorderHeightUntilRow(int $_y): int
	{
		$totalBorderHeight = 0;

		foreach ($this->getSortedBorderRowIds() as $y)
		{
			if ($y > $_y) break;
			$totalBorderHeight += $this->getMaximumBorderHeightInRow($y);
		}

		return $totalBorderHeight;
	}

	/**
	 * Returns a list of sorted border column ids.
	 *
	 * @return int[] The list of sorted border column ids
	 */
	protected function getSortedBorderColumnIds(): array
	{
		$columnIds = array();

		foreach ($this->borderPositionsGrid as $borderPositionRow)
		{
			$columnIds = array_merge($columnIds, array_keys($borderPositionRow));
		}

		$columnIds = array_unique($columnIds);
		natsort($columnIds);

		return $columnIds;
	}

	/**
	 * Returns a list of sorted border row ids.
	 *
	 * @return int[] The list of sorted border row ids
	 */
	public function getSortedBorderRowIds(): array
	{
		$rowIds = array_keys($this->borderPositionsGrid);
		natsort($rowIds);

		return $rowIds;
	}

	public function getLowestColumnId()
	{
		$columnIds = $this->getSortedBorderColumnIds();

		if ($columnIds) return $columnIds[0];
		else return null;
	}

	public function getHighestColumnId()
	{
		$columnIds = $this->getSortedBorderColumnIds();
		return array_pop($columnIds);
	}

	public function getLowestRowId()
	{
		$rowIds = $this->getSortedBorderRowIds();
		if ($rowIds) return $rowIds[0];
		else return null;
	}

	public function getHighestRowId()
	{
		$rowIds = $this->getSortedBorderRowIds();
		return array_pop($rowIds);
	}

	// TODO: Export column/row id stuff to "Grid" class
}
