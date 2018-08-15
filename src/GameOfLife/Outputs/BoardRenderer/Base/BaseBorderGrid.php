<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;
use GameOfLife\Board;
use GameOfLife\Coordinate;

/**
 * Stores the rendered borders and the rendered background grid.
 */
abstract class BaseBorderGrid
{
	// Attributes

	/**
	 * The list of border parts
	 *
	 * @var BaseBorderPart[] $borderParts
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


	// Magic Methods

	/**
	 * BaseBorderGrid constructor.
	 *
	 * @param Board $_board The board
	 * @param BaseBorder $_border The main border
	 */
	public function __construct(Board $_board, $_border)
	{
		$this->borderParts = array();
		$this->borderPositionsGrid = array();
		$this->renderedBorderParts = array();
	}


	// Class methods

	/**
	 * Adds a border part to this border grid.
	 *
	 * @param BaseBorderPart $_borderPart The border part
	 */
	public function addBorderPart($_borderPart)
	{
		$this->borderParts[] = $_borderPart;
	}

	/**
	 * Returns the background grid border parts.
	 *
	 * @param Board $_board The board
	 * @param BaseBorder $_border The border
	 *
	 * @return BaseBorderPart[] The background grid border parts
	 */
	public function getBackgroundGridBorderParts(Board $_board, $_border): array
	{
		$backgroundGridBorderParts = array();

		// Add horizontal border parts
		for ($y = 1; $y < $_board->height(); $y++)
		{
			$borderPart = $this->getHorizontalBackgroundGridBorderPart(
				new Coordinate(0, $y),
				new Coordinate($_board->width() - 1, $y),
				$_border
			);
			$backgroundGridBorderParts[] = $borderPart;
		}

		// Add vertical border parts
		for ($x = 1; $x < $_board->width(); $x++)
		{
			$borderPart = $this->getVerticalBackgroundGridBorderPart(
				new Coordinate($x, 0),
				new Coordinate($x, $_board->height() - 1),
				$_border
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
	 * @return mixed The rendered border grid
	 */
	abstract public function renderBorderGrid();

	/**
	 * Renders all border parts.
	 * Must be called in renderBorderGrid() implementations.
	 */
	protected function renderBorderParts()
	{
		if (! $this->renderedBorderParts)
		{
			foreach ($this->borderParts as $borderPart)
			{
				$renderedBorderPart = $borderPart->getRenderedBorderPart();

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

		for ($x = -1; $x < $_x; $x++)
		{
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

		for ($y = -1; $y < $_y; $y++)
		{
			$totalBorderHeight += $this->getMaximumBorderHeightInRow($y);
		}

		return $totalBorderHeight;
	}
}
