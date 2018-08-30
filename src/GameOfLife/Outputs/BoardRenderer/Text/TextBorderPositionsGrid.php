<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use BoardRenderer\Base\BaseBorderPositionsGrid;

/**
 * Border positions grid for text border grids.
 *
 * This border positions grid contains two rows for each board field row and two columns for each board field column
 *
 * The border symbol rows are located above the corresponding board field row.
 * The border symbol columns are located left to the corresponding board field column.
 */
class TextBorderPositionsGrid extends BaseBorderPositionsGrid
{
	// Class Methods

	/**
	 * Calculates and returns the number of covered horizontal board fields based on a start and end column id.
	 *
	 * @param int $_startColumnId The id of the start column
	 * @param int $_endColumnId The id of the end column
	 *
	 * @return int The number of covered horizontal board fields
	 */
	protected function calculateNumberOfCoveredHorizontalBoardFields(int $_startColumnId, int $_endColumnId): int
	{
		$startColumnId = $_startColumnId;
		$endColumnId = $_endColumnId;

		if ($startColumnId % 2 == 0)
		{ // The start column id is a border column id so it must be increased by one to get the id of the board field column right to it
			$startColumnId += 1;
		}

		if ($endColumnId % 2 == 0)
		{ // The end column id is a border column id so it must be decreased by one to get the id of the board field column left from it
			$endColumnId -= 1;
		}

		$numberOfCoveredBoardFields = ($endColumnId - $startColumnId) / 2;
		if ($numberOfCoveredBoardFields < 0) $numberOfCoveredBoardFields = 0;

		return $numberOfCoveredBoardFields;
	}

	/**
	 * Calculates and returns the number of covered horizontal board fields based on a start and end row id.
	 *
	 * @param int $_startRowId The id of the start row
	 * @param int $_endRowId The id of the end row
	 *
	 * @return int The number of covered vertical board fields
	 */
	protected function calculateNumberOfCoveredVerticalBoardFields(int $_startRowId, int $_endRowId): int
	{
		$startRowId = $_startRowId;
		$endRowId = $_endRowId;

		if ($startRowId % 2 == 0)
		{ // The start row id is a border row id so it must be increased by one to get the id of the board field row below it
			$startRowId += 1;
		}

		if ($endRowId % 2 == 0)
		{  // The end row id is a border row id so it must be decreased by one to get the id of the board field row above it
			$endRowId -= 1;
		}

		$numberOfCoveredBoardFields = ($endRowId - $startRowId) / 2;
		if ($numberOfCoveredBoardFields < 0) $numberOfCoveredBoardFields = 0;

		return $numberOfCoveredBoardFields;
	}
}
