<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use GameOfLife\Board;

/**
 * Stores information about the grid positions that are covered by borders.
 * Also provides methods to get information about the covered grid positions.
 */
class BorderPositionsGrid
{
	// Attributes

	/**
	 * The grid of border positions
	 *
	 * There is a border position for each board field (however only border positions that are covered by borders are defined)
	 * The fields in this array are positioned "left to and above the corresponding cell"
	 *
	 * There can also be border positions that exceed the board fields
	 *
	 * @var BorderPartThickness[][] $borderPositions
	 */
	protected $borderPositions;

	/**
	 * The board for which this border positions grid will be used
	 * This attribute is used to determine the width and height of the border positions grid
	 *
	 * @var Board $board
	 */
	protected $board;


	// Magic Methods

	/**
	 * BorderPositionsGrid constructor.
	 *
	 * @param Board $_board The board for which this border positions grid will be used
	 */
	public function __construct(Board $_board)
	{
		$this->board = $_board;
		$this->borderPositions = array();
	}


	// Class Methods

	/**
	 * Adds a rendered border part to this border positions grid.
	 *
	 * @param RenderedBorderPart $_renderedBorderPart The rendered border part
	 */
	public function addRenderedBorderPart($_renderedBorderPart)
	{
		foreach ($_renderedBorderPart->borderPartGridPositions() as $at)
		{
			if (! isset($this->borderPositions[$at->y()]))
			{
				$this->borderPositions[$at->y()] = array();
			}

			$borderThickness = $_renderedBorderPart->parentBorderPart()->thickness();

			if (isset($this->borderPositions[$at->y()][$at->x()]))
			{
				$borderPositionThickness = $this->borderPositions[$at->y()][$at->x()];

				if ($borderPositionThickness->width() < $borderThickness->width())
				{
					$borderPositionThickness->setWidth($borderThickness->width());
				}
				if ($borderPositionThickness->height() < $borderThickness->height())
				{
					$borderPositionThickness->setHeight($borderPositionThickness->height());
				}
			}
			else $this->borderPositions[$at->y()][$at->x()] = clone $borderThickness;
		}
	}

	/**
	 * Resets the border positions to an empty array.
	 */
	public function reset()
	{
		$this->borderPositions = array();
	}


	// Fetch row ids
	// TODO: Use board attribute

	/**
	 * Returns a list of sorted border row ids.
	 *
	 * @return int[] The list of sorted border row ids
	 */
	protected function getSortedBorderRowIds(): array
	{
		$rowIds = array_keys($this->borderPositions);
		natsort($rowIds);

		return $rowIds;
	}

	/**
	 * Returns the lowest row id inside the border grid.
	 *
	 * @return int|null The lowest row id or null if there are no rows
	 */
	public function getLowestRowId()
	{
		$rowIds = $this->getSortedBorderRowIds();
		if ($rowIds) return $rowIds[0];
		else return null;
	}

	/**
	 * Returns the highest row id inside the border grid.
	 *
	 * @return int|null The highest row id or null if there are no rows
	 */
	public function getHighestRowId()
	{
		$rowIds = $this->getSortedBorderRowIds();
		return array_pop($rowIds);
	}


	// Fetch column ids
	// TODO: Use board attribute

	/**
	 * Returns a list of sorted border column ids.
	 *
	 * @return int[] The list of sorted border column ids
	 */
	protected function getSortedBorderColumnIds(): array
	{
		$columnIds = array();
		foreach ($this->borderPositions as $borderPositionRow)
		{
			$columnIds = array_merge($columnIds, array_keys($borderPositionRow));
		}

		$columnIds = array_unique($columnIds);
		natsort($columnIds);

		return $columnIds;
	}

	/**
	 * Returns the lowest column id inside the border grid.
	 *
	 * @return int|null The lowest column id or null if there are no columns
	 */
	public function getLowestColumnId()
	{
		$columnIds = $this->getSortedBorderColumnIds();

		if ($columnIds) return $columnIds[0];
		else return null;
	}

	/**
	 * Returns the highest column id inside the border grid.
	 *
	 * @return int|null The highest column id or null if there are no columns
	 */
	public function getHighestColumnId()
	{
		$columnIds = $this->getSortedBorderColumnIds();
		return array_pop($columnIds);
	}


	// Fetch border thicknesses

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
		if (isset($this->borderPositions[$_y]))
		{
			foreach ($this->borderPositions[$_y] as $x => $borderHeight)
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
		foreach ($this->borderPositions as $y => $borderPositionsRow)
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
}
