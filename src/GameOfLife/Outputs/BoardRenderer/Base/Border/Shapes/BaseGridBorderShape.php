<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\Shapes;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use GameOfLife\Board;
use GameOfLife\Coordinate;

/**
 * Base class for background grid border shapes.
 */
abstract class BaseGridBorderShape extends BaseBorderShape
{
	private $board;

	public function __construct(BaseBorder $_parentBorder, Board $_board)
	{
		parent::__construct($_parentBorder);
		$this->board = $_board;
	}

	public function getBorderParts()
	{
		$backgroundGridBorderParts = array();

		// Add horizontal border parts
		for ($y = 1; $y < $this->board->height(); $y++)
		{
			$borderPart = $this->getHorizontalBackgroundGridBorderPart(
				new Coordinate(0, $y),
				new Coordinate($this->board->width() - 1, $y),
				$this->parentBorder
			);
			$backgroundGridBorderParts[] = $borderPart;
		}

		// Add vertical border parts
		for ($x = 1; $x < $this->board->width(); $x++)
		{
			$borderPart = $this->getVerticalBackgroundGridBorderPart(
				new Coordinate($x, 0),
				new Coordinate($x, $this->board->height() - 1),
				$this->parentBorder
			);
			$backgroundGridBorderParts[] = $borderPart;
		}

		return $backgroundGridBorderParts;
	}

	/**
	 * Returns a horizontal border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param BaseBorder $_parentBorder The main border
	 *
	 * @return BaseBorderPart The horizontal border part
	 */
	abstract protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder);

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 * @param BaseBorder $_parentBorder The main border
	 *
	 * @return BaseBorderPart The vertical border part
	 */
	abstract protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt, $_parentBorder);


	public function getBorderWidthInColumn(int $_x): int
	{
		// TODO: Fix fixed number ...
		if ($_x > 0 && $_x < $this->board->width()) return 1;
		else return 0;
	}

	public function getBorderHeightInRow(int $_y): int
	{
		// TODO: Fix fixed number ...
		if ($_y > 0 && $_y < $this->board->height()) return 1;
		else return 0;
	}
}
