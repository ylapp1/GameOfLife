<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use GameOfLife\Coordinate;

/**
 * Defines additional necessary methods for TextBorderPartShape classes.
 * Must be implemented by all TextBorderPartShape classes.
 */
interface TextBorderPartShapeInterface
{
	// Class Methods

	/**
	 * Calculates and returns the number of border symbols that are necessary to render the parent border part with this shape not including start and end edges.
	 *
	 * @return int The number of border symbols that are necessary to render the parent border part with this shape not including start and end edges
	 */
	public function getNumberOfBorderSymbols(): int;

	/**
	 * Returns the position of a coordinate inside the list of border symbols of the parent border part.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return int|null The position of the coordinate inside the list of border symbols of the parent border part or null if the coordinate is not inside the parent border part
	 */
	public function getBorderSymbolPositionOf(Coordinate $_coordinate);
}
