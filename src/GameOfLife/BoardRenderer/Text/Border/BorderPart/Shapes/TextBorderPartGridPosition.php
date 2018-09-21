<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use Utils\Geometry\Coordinate;

/**
 * Converts a board field coordinate to a text border positions grid coordinate.
 *
 * The border symbol rows are located above the corresponding board field row.
 * The border symbol columns are located left to the corresponding board field column.
 */
class TextBorderPartGridPosition extends Coordinate
{
	/**
	 * TextBorderPartGridPosition constructor.
	 *
	 * @param Coordinate $_at The position of the border part in the board field grid
	 * @param Bool $_isInCellSymbolRow If true the Y-Coordinate will be set to the corresponding cell symbol row in the border positions grid
	 * @param Bool $_isInCellSymbolColumn If true the X-Coordinate will be set to the corresponding cell symbol column in the border positions grid
	 */
	public function __construct(Coordinate $_at, Bool $_isInCellSymbolRow, Bool $_isInCellSymbolColumn)
	{
		$xPosition = $_at->x() * 2;
		if ($_isInCellSymbolColumn) $xPosition += 1;

		$yPosition = $_at->y() * 2;
		if ($_isInCellSymbolRow) $yPosition += 1;

		parent::__construct($xPosition, $yPosition);
	}
}
