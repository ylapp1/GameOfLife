<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\Shapes;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\Shapes\BaseBorderShape;
use BoardRenderer\Text\Border\BorderPart\TextHorizontalBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextVerticalBorderPart;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use Utils\Geometry\Coordinate;

/**
 * Prints the highlight field border for TextBoardRenderer classes.
 */
class TextHighLightFieldBorderShape extends BaseBorderShape
{
	/**
	 * The highlight field coordinate
	 *
	 * @var Coordinate $highLightFieldCoordinate
	 */
	private $highLightFieldCoordinate;

	/**
	 * The border symbol definition for the top and bottom border part
	 *
	 * @var BorderSymbolDefinition $borderTopBottomSymbolDefinition
	 */
	private $borderTopBottomSymbolDefinition;

	/**
	 * The border symbol definition for the left and right border part
	 *
	 * @var BorderSymbolDefinition $borderLeftRightSymbolDefinition
	 */
	private $borderLeftRightSymbolDefinition;


	// Magic Methods

	/**
	 * TextHighLightFieldBorderShape constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border
	 * @param BorderSymbolDefinition $_borderTopBottomSymbolDefinition The border symbol definition for the top and bottom border part
	 * @param BorderSymbolDefinition $_borderLeftRightSymbolDefinition The border symbol definition for the left and right border part
	 * @param Coordinate $_highLightFieldCoordinate The highlight field coordinate
	 */
	public function __construct(BaseBorder $_parentBorder, BorderSymbolDefinition $_borderTopBottomSymbolDefinition, BorderSymbolDefinition $_borderLeftRightSymbolDefinition, Coordinate $_highLightFieldCoordinate)
	{
		parent::__construct($_parentBorder);
		$this->borderTopBottomSymbolDefinition = $_borderTopBottomSymbolDefinition;
		$this->borderLeftRightSymbolDefinition = $_borderLeftRightSymbolDefinition;
		$this->highLightFieldCoordinate = $_highLightFieldCoordinate;
	}


	// Class Methods

	/**
	 * Returns all border parts of this border shape.
	 * Must be called after the highlight field coordinate was set.
	 *
	 * @return BaseBorderPart[] The list of border parts
	 */
	public function getBorderParts(): array
	{
		return array(
			// Top
			$this->getHorizontalBorderPart($this->highLightFieldCoordinate->y()),

			// Bottom
			$this->getHorizontalBorderPart($this->highLightFieldCoordinate->y() + 1),

			// Left
			$this->getVerticalBorderPart($this->highLightFieldCoordinate->x()),

			// Right
			$this->getVerticalBorderPart($this->highLightFieldCoordinate->x() + 1),

			// Column id above the border
			$this->getColumnIdBorderPart($this->highLightFieldCoordinate->x()),

			// Row id right to the border
			$this->getRowIdBorderPart($this->highLightFieldCoordinate->y())
		);
	}

	/**
	 * Returns a horizontal border part for a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return TextHorizontalBorderPart The horizontal border part
	 */
	private function getHorizontalBorderPart(int $_y): TextHorizontalBorderPart
	{
		// TODO: If start/end null
		$startsAt = new Coordinate(
			$this->getStartX($_y),
			$_y
		);
		$endsAt = new Coordinate(
			$this->getEndX($_y),
			$_y
		);

		return new TextHorizontalBorderPart(
			$this,
			$startsAt,
			$endsAt,
			new BorderPartThickness(1, 1),
			$this->borderTopBottomSymbolDefinition
		);
	}

	/**
	 * Returns a vertical border part for a specific row.
	 *
	 * @param int $_x The X-Coordinate of the row
	 *
	 * @return TextVerticalBorderPart The vertical border part
	 */
	private function getVerticalBorderPart(int $_x): TextVerticalBorderPart
	{
		// TODO: If start/end null
		$startsAt = new Coordinate(
			$_x,
			$this->getStartY($_x)
		);
		$endsAt = new Coordinate(
			$_x,
			$this->getEndy($_x)
		);

		return new TextVerticalBorderPart(
			$this,
			$startsAt,
			$endsAt,
			new BorderPartThickness(1, 1),
			$this->borderLeftRightSymbolDefinition
		);
	}

	/**
	 * Returns the border part that shows the column id above the board.
	 *
	 * @param int $_x The X-Coordinate to show above the board
	 *
	 * @return TextHorizontalBorderPart The border part that shows the column id above the board
	 */
	private function getColumnIdBorderPart(int $_x): TextHorizontalBorderPart
	{
		// TODO: endY - borderthickness->y() instead of - 1
		return new TextHorizontalBorderPart(
			$this,
			new Coordinate($_x, $this->getStartY($_x) - 1),
			new Coordinate($_x + 1, $this->getStartY($_x) - 1),
			new BorderPartThickness(1, 1),
			new BorderSymbolDefinition(
				" ",
				$_x,
				" ",
				array()
			)
		);
	}

	/**
	 * Returns the border part that shows the row id right to the board.
	 *
	 * @param int $_y The Y-Coordinate to show right to the board
	 *
	 * @return TextVerticalBorderPart The border part that shows the row id right to the board
	 */
	private function getRowIdBorderPart(int $_y): TextVerticalBorderPart
	{
		// TODO: endX + borderthickness->x() + 2 instead of + 1 + 1
		return new TextVerticalBorderPart(
			$this,
			new Coordinate($this->getEndX($_y) + 1 , $_y),
			new Coordinate($this->getEndX($_y) + 1, $_y + 1),
			new BorderPartThickness(1, 1),
			new BorderSymbolDefinition(
				" ",
				$_y,
				" ",
				array()
			)
		);
	}

	/**
	 * Calculates and returns the start Y-Coordinate in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The start Y-Coordinate
	 */
	public function getStartY(int $_x)
	{
		return $this->parentBorder->parentBorder()->shape()->getStartY($_x);
	}

	/**
	 * Calculates and returns the end Y-Coordinate in a specific column.
	 *
	 * @param int $_x The X-Coordinate of the column
	 *
	 * @return int|null The end Y-Coordinate
	 */
	public function getEndY(int $_x)
	{
		return $this->parentBorder->parentBorder()->shape()->getEndY($_x);
	}

	/**
	 * Calculates and returns the start X-Coordinate in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The start X-Coordinate
	 */
	public function getStartX(int $_y)
	{
		return $this->parentBorder->parentBorder()->shape()->getStartX($_y);
	}

	/**
	 * Calculates and returns the end X-Coordinate in a specific row.
	 *
	 * @param int $_y The Y-Coordinate of the row
	 *
	 * @return int|null The start X-Coordinate
	 */
	public function getEndX(int $_y)
	{
		return $this->parentBorder->parentBorder()->shape()->getEndX($_y);
	}

	/**
	 * Returns the row ids that are covered by this border shape.
	 *
	 * @return int[] The list of row ids
	 */
	public function getRowIds(): array
	{
		// TODO: If start/end null
		$rowIds = array();
		$columnIds = array($this->highLightFieldCoordinate->x(), $this->highLightFieldCoordinate->x() + 1);
		foreach ($columnIds as $x)
		{
			for ($y = $this->getStartY($x); $y <= $this->getEndY($x); $y++)
			{
				$rowIds[] = new Coordinate($x, $y);
			}
		}

		return $rowIds;
	}

	/**
	 * Returns the column ids that are covered by this border shape.
	 *
	 * @return int[] The list of column ids
	 */
	public function getColumnIds(): array
	{
		// TODO: If start/end null
		$columnIds = array();
		$rowIds = array($this->highLightFieldCoordinate->y(), $this->highLightFieldCoordinate->y() + 1);
		foreach ($rowIds as $y)
		{
			for ($x = $this->getStartX($y); $x <= $this->getEndX($y); $x++)
			{
				$columnIds[] = new Coordinate($x, $y);
			}
		}

		return $columnIds;
	}
}
