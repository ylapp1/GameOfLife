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
abstract class BaseBorderPositionsGrid
{
	// Attributes

	/**
	 * The grid of border positions
	 * Stores the maximum combination of thickness height and width at each position
	 *
	 * @var BorderPartThickness[][] $borderThicknessRows
	 */
	protected $borderThicknessRows;

	/**
	 * The board for which this border positions grid will be used
	 *
	 * @var Board $board
	 */
	protected $board;


	// Magic Methods

	/**
	 * BaseBorderPositionsGrid constructor.
	 *
	 * @param Board $_board The board for which this border positions grid will be used
	 */
	public function __construct(Board $_board)
	{
		$this->borderThicknessRows = array();
		$this->board = $_board;
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
			if (! isset($this->borderThicknessRows[$at->y()]))
			{
				$this->borderThicknessRows[$at->y()] = array();
			}

			$borderThickness = $_renderedBorderPart->parentBorderPart()->thickness();

			if (isset($this->borderThicknessRows[$at->y()][$at->x()]))
			{
				$borderPositionThickness = $this->borderThicknessRows[$at->y()][$at->x()];

				if ($borderPositionThickness->width() < $borderThickness->width())
				{
					$borderPositionThickness->setWidth($borderThickness->width());
				}
				if ($borderPositionThickness->height() < $borderThickness->height())
				{
					$borderPositionThickness->setHeight($borderPositionThickness->height());
				}
			}
			else $this->borderThicknessRows[$at->y()][$at->x()] = clone $borderThickness;
		}
	}

	/**
	 * Resets the border positions to an empty array.
	 */
	public function reset()
	{
		$this->borderThicknessRows = array();
	}


	// Fetch row ids for all columns

	/**
	 * Returns a sorted list of all row ids.
	 *
	 * @return int[] The list of all row ids
	 */
	protected function getAllSortedRowIds(): array
	{
		$rowIds = array_keys($this->borderThicknessRows);
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
		$rowIds = $this->getAllSortedRowIds();
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
		$rowIds = $this->getAllSortedRowIds();
		return array_pop($rowIds);
	}


	// Fetch row ids for a specific column

	/**
	 * Returns all row ids in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int[] The list of row ids
	 */
	protected function getAllSortedRowIdsInColumn(int $_x): array
	{
		$rowIds = array();
		foreach ($this->borderThicknessRows as $y => $borderThicknessRow)
		{
			if (isset($borderThicknessRow[$_x])) $rowIds[] = $y;
		}

		natsort($rowIds);

		return $rowIds;
	}

	/**
	 * Returns the lowest row id in a specific column inside the border grid.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The lowest row id or null if there are no rows in this column
	 */
	protected function getLowestRowIdInColumn(int $_x)
	{
		$rowIds = $this->getAllSortedRowIdsInColumn($_x);
		if ($rowIds) return $rowIds[0];
		else return null;
	}

	/**
	 * Returns the highest row id in a specific column inside the border grid.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The highest row id or null if there are no rows in this column
	 */
	public function getHighestRowIdInColumn(int $_x)
	{
		$rowIds = $this->getAllSortedRowIdsInColumn($_x);
		return array_pop($rowIds);
	}


	// Fetch column ids for all rows

	/**
	 * Returns a list of sorted border column ids.
	 *
	 * @return int[] The list of sorted border column ids
	 */
	protected function getAllSortedColumnIds(): array
	{
		$columnIds = array();
		foreach ($this->borderThicknessRows as $borderThicknessRow)
		{
			$columnIds = array_merge($columnIds, array_keys($borderThicknessRow));
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
		$columnIds = $this->getAllSortedColumnIds();
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
		$columnIds = $this->getAllSortedColumnIds();
		return array_pop($columnIds);
	}


	// Fetch column ids for a specific row

	/**
	 * Returns all column ids in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int[] The list of column ids
	 */
	protected function getAllSortedColumnIdsInRow(int $_y): array
	{
		$columnIds = array();
		if (isset($this->borderThicknessRows[$_y]))
		{
			$columnIds = array_keys($this->borderThicknessRows[$_y]);
			natsort($columnIds);
		}

		return $columnIds;
	}

	/**
	 * Returns the lowest column id in a specific row inside the border grid.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The lowest column id or null if there are no columns in this row
	 */
	public function getLowestColumnIdInRow(int $_y)
	{
		$columnIds = $this->getAllSortedColumnIdsInRow($_y);
		if ($columnIds) return $columnIds[0];
		else return null;
	}

	/**
	 * Returns the highest column id in a specific row inside the border grid.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The highest column id or null if there are no columns in this row
	 */
	public function getHighestColumnIdInRow(int $_y)
	{
		$columnIds = $this->getAllSortedColumnIdsInRow($_y);
		return array_pop($columnIds);
	}


	// Fetch number of covered board fields

	/**
	 * Returns the number of horizontal board fields that are covered in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The number of covered board fields
	 */
	public function getNumberOfCoveredHorizontalBoardFields(int $_y): int
	{
		$numberOfCoveredHorizontalBoardFields = 0;

		$startColumnId = $this->getLowestColumnIdInRow($_y);
		if ($startColumnId !== null)
		{
			if ($startColumnId < 0) $startColumnId = 0;

			$endColumnId = $this->getHighestColumnIdInRow($_y);
			if ($endColumnId > $this->board->width()) $endColumnId = $this->board->width();

			$numberOfCoveredHorizontalBoardFields = $this->calculateNumberOfCoveredHorizontalBoardFields($startColumnId, $endColumnId);
		}

		return $numberOfCoveredHorizontalBoardFields;
	}

	/**
	 * Calculates and returns the number of covered horizontal board fields based on a start and end column id.
	 *
	 * @param int $_startColumnId The id of the start column
	 * @param int $_endColumnId The id of the end column
	 *
	 * @return int The number of covered horizontal board fields
	 */
	abstract protected function calculateNumberOfCoveredHorizontalBoardFields(int $_startColumnId, int $_endColumnId): int;

	/**
	 * Returns the number of vertical board fields that are covered in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The number of covered board fields
	 */
	public function getNumberOfCoveredVerticalBoardFields(int $_x): int
	{
		$numberOfCoveredVerticalBoardFields = 0;

		$startRowId = $this->getLowestRowIdInColumn($_x);
		if ($startRowId !== null)
		{
			if ($startRowId < 0) $startRowId = 0;

			$endRowId = $this->getHighestRowIdInColumn($_x);
			if ($endRowId > $this->board->height()) $endRowId = $this->board->height();

			$numberOfCoveredVerticalBoardFields = $this->calculateNumberOfCoveredVerticalBoardFields($startRowId, $endRowId);
		}

		return $numberOfCoveredVerticalBoardFields;
	}

	/**
	 * Calculates and returns the number of covered horizontal board fields based on a start and end row id.
	 *
	 * @param int $_startRowId The id of the start row
	 * @param int $_endRowId The id of the end row
	 *
	 * @return int The number of covered horizontal board fields
	 */
	abstract protected function calculateNumberOfCoveredVerticalBoardFields(int $_startRowId, int $_endRowId): int;

	/**
	 * Returns the maximum number of covered horizontal board fields.
	 *
	 * @return int The maximum number of covered horizontal board fields
	 */
	public function getMaximumNumberOfCoveredHorizontalBoardFields(): int
	{
		$maximumNumberOfCoveredHorizontalBoardFields = 0;
		foreach ($this->getAllSortedRowIds() as $y)
		{
			$numberOfCoveredHorizontalBoardFields = $this->getNumberOfCoveredHorizontalBoardFields($y);
			if ($numberOfCoveredHorizontalBoardFields > $maximumNumberOfCoveredHorizontalBoardFields)
			{
				$maximumNumberOfCoveredHorizontalBoardFields = $numberOfCoveredHorizontalBoardFields;
			}
		}

		return $maximumNumberOfCoveredHorizontalBoardFields;
	}

	/**
	 * Returns the maximum number of covered vertical board fields.
	 *
	 * @return int The maximum number of covered vertical board fields
	 */
	public function getMaximumNumberOfCoveredVerticalBoardFields(): int
	{
		$maximumNumberOfCoveredVerticalBoardFields = 0;
		foreach ($this->getAllSortedColumnIds() as $x)
		{
			$numberOfCoveredVerticalBoardFields = $this->getNumberOfCoveredVerticalBoardFields($x);
			if ($numberOfCoveredVerticalBoardFields > $maximumNumberOfCoveredVerticalBoardFields)
			{
				$maximumNumberOfCoveredVerticalBoardFields = $numberOfCoveredVerticalBoardFields;
			}
		}

		return $maximumNumberOfCoveredVerticalBoardFields;
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
		if (isset($this->borderThicknessRows[$_y]))
		{
			foreach ($this->borderThicknessRows[$_y] as $x => $borderThickness)
			{
				if ($borderThickness->height() > $maximumBorderHeight) $maximumBorderHeight = $borderThickness->height();
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
		foreach ($this->borderThicknessRows as $y => $borderPositionsRow)
		{
			if (isset($borderPositionsRow[$_x]))
			{
				$borderThickness = $borderPositionsRow[$_x];
				if ($borderThickness->width() > $maximumBorderWidth) $maximumBorderWidth = $borderThickness->width();
			}
		}

		return $maximumBorderWidth;
	}

	/**
	 * Returns the total maximum border width until a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int The total maximum border width until that column
	 */
	public function getTotalMaximumBorderWidthUntilColumn(int $_x): int
	{
		$totalBorderWidth = 0;
		foreach ($this->getAllSortedColumnIds() as $x)
		{
			if ($x > $_x) break;
			$totalBorderWidth += $this->getMaximumBorderWidthInColumn($x);
		}

		return $totalBorderWidth;
	}

	/**
	 * Returns the total maximum border height until a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int The total maximum border height until that row
	 */
	public function getTotalMaximumBorderHeightUntilRow(int $_y): int
	{
		$totalBorderHeight = 0;
		foreach ($this->getAllSortedRowIds() as $y)
		{
			if ($y > $_y) break;
			$totalBorderHeight += $this->getMaximumBorderHeightInRow($y);
		}

		return $totalBorderHeight;
	}
}
