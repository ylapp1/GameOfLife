<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image;

use BoardRenderer\Base\BaseBorderPositionsGrid;

/**
 * Border positions grid for images.
 *
 * There is a border position for each board field (however only border positions that are covered by borders are defined)
 * The fields in this array are positioned "left to and above the corresponding cell"
 *
 * There can also be border positions that exceed the board fields
 */
class ImageBorderPositionsGrid extends BaseBorderPositionsGrid
{
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
		return $_endColumnId - $_startColumnId;
	}

	/**
	 * Calculates and returns the number of covered horizontal board fields based on a start and end row id.
	 *
	 * @param int $_startRowId The id of the start row
	 * @param int $_endRowId The id of the end row
	 *
	 * @return int The number of covered horizontal board fields
	 */
	protected function calculateNumberOfCoveredVerticalBoardFields(int $_startRowId, int $_endRowId): int
	{
		return $_endRowId - $_startRowId;
	}
}
