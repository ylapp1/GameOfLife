<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\OutputBorderPart;

use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\SymbolGrid\BorderSymbolGrid;

/**
 * Class for horizontal border parts.
 */
class HorizontalOutputBorderPart extends OutputBorderPart
{
	// Magic Methods

	/**
	 * HorizontalOutputBorderPart constructor.
	 *
	 * @param Coordinate $_startsAt The start coordinate of this border
	 * @param Coordinate $_endsAt The end coordinate of this border
	 * @param String $_borderSymbolStart The symbol for the start of the border
	 * @param String $_borderSymbolCenter The symbol for the center parts of the border
	 * @param String $_borderSymbolEnd The symbol for the end of the border
	 * @param String $_borderSymbolOuterBorderCollisionStart The symbol for the start of the border when the start collides with an outer border
	 * @param String $_borderSymbolOuterBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an outer border
	 * @param String $_borderSymbolOuterBorderCollisionEnd The symbol for the end of the border when the end collides with an outer border
	 * @param String $_borderSymbolInnerBorderCollisionStart The symbol for the start of the border when the start collides with an inner border
	 * @param String $_borderSymbolInnerBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an inner border
	 * @param String $_borderSymbolInnerBorderCollisionEnd The symbol for the end of the border when the end collides with an inner border
	 */
	public function __construct(Coordinate $_startsAt, Coordinate $_endsAt, String $_borderSymbolStart, String $_borderSymbolCenter, String $_borderSymbolEnd, String $_borderSymbolOuterBorderCollisionStart, String $_borderSymbolOuterBorderCollisionCenter, String $_borderSymbolOuterBorderCollisionEnd, String $_borderSymbolInnerBorderCollisionStart, String $_borderSymbolInnerBorderCollisionCenter, String $_borderSymbolInnerBorderCollisionEnd)
	{
		parent::__construct($_startsAt, $_endsAt, $_borderSymbolStart, $_borderSymbolCenter, $_borderSymbolEnd, $_borderSymbolOuterBorderCollisionStart, $_borderSymbolOuterBorderCollisionCenter, $_borderSymbolOuterBorderCollisionEnd, $_borderSymbolInnerBorderCollisionStart, $_borderSymbolInnerBorderCollisionCenter, $_borderSymbolInnerBorderCollisionEnd);
	}


	// Class Methods

	/**
	 * Returns the position at which this border collides with a border or null if the borders don't collide.
	 *
	 * @param OutputBorderPart $_border The border
	 *
	 * @return int|null The position at which this border collides with a border or null if the borders don't collide
	 */
	public function collidesWith(OutputBorderPart $_border)
	{
		if ($_border->startsAt()->y() == $this->startsAt->y())
		{
			if ($_border->startsAt()->x() >= $this->startsAt->x() &&
				$_border->startsAt()->x() < $this->startsAt->x() + count($this->borderCollisionSymbols))
			{
				return $_border->startsAt()->x() - $this->startsAt->x();
			}
		}

		return null;
	}

	/**
	 * Calculates and returns the length of this border without start/end symbols.
	 *
	 * @return int The length of this border without start/end symbols
	 */
	protected function getBorderLength(): int
	{
		$totalLength = $this->endsAt->x() - ($this->startsAt->x() - 1);
		return $totalLength - 2;
	}

	/**
	 * Returns whether the output border contains a specific coordinate between its left and right edge.
	 * This does not include the coordinates of the left and right edge.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the output border contains the coordinate, false otherwise
	 */
	public function containsCoordinateBetweenEdges(Coordinate $_coordinate): Bool
	{
		if ($this->startsAt->y() == $_coordinate->y() && $this->endsAt->y() == $_coordinate->y() &&
			$this->startsAt->x() < $_coordinate->x() && $this->endsAt->x() > $_coordinate->x())
		{
			return true;
		}
		else return false;
	}

	/**
	 * Adds the border symbols of this border to a border symbol grid.
	 *
	 * @param BorderSymbolGrid $_borderSymbolGrid The border symbol grid
	 */
	public function addBorderSymbolsToBorderSymbolGrid(BorderSymbolGrid $_borderSymbolGrid)
	{
		$startX = $this->startsAt->x();
		$startY = $this->startsAt->y() * 2;
		$totalBorderLength = $this->getTotalBorderLength();

		// TODO: Only if has left border
		if (isset($this->borderCollisionSymbols[$startX])) $borderSymbolStart = $this->borderCollisionSymbols[$startX];
		else $borderSymbolStart = $this->borderSymbolStart;

		$_borderSymbolGrid->setSymbolAt(new Coordinate($startX, $startY), $borderSymbolStart);


		for ($x = $startX + 1; $x < $startX + $totalBorderLength; $x++)
		{
			if (isset($this->borderCollisionSymbols[$x])) $borderSymbol = $this->borderCollisionSymbols[$x];
			else $borderSymbol = $this->borderSymbolCenter;

			$_borderSymbolGrid->setSymbolAt(new Coordinate($x, $startY), $borderSymbol);
		}

		// TODO: Only if has right border
		$borderSymbolEndIndex = $startX + $totalBorderLength;

		if (isset($this->borderCollisionSymbols[$totalBorderLength]))
		{
			$borderSymbolEnd = $this->borderCollisionSymbols[$totalBorderLength];
		}
		else $borderSymbolEnd = $this->borderSymbolEnd;

		$_borderSymbolGrid->setSymbolAt(new Coordinate($borderSymbolEndIndex, $startY), $borderSymbolEnd);
	}
}
