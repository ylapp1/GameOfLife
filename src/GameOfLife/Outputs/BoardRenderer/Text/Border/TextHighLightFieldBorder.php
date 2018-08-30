<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\Border\BorderPart\CollisionDirection;
use BoardRenderer\Text\Border\Shapes\TextHighLightFieldBorderShape;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use BoardRenderer\Text\Border\SymbolDefinition\CollisionSymbolDefinition;
use GameOfLife\Coordinate;

/**
 * The border that is used to print a high light field.
 */
class TextHighLightFieldBorder extends BaseBorder
{
	// Magic Methods

	/**
	 * TextBackgroundGridBorder constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border of this border
	 * @param Coordinate $_highLightFieldCoordinate The highlight field coordinate
	 */
	public function __construct(BaseBorder $_parentBorder = null, Coordinate $_highLightFieldCoordinate)
	{
		parent::__construct(
			$_parentBorder,
			new TextHighLightFieldBorderShape(
				$this,
				$this->getBorderHorizontalSymbolDefinition(),
				$this->getBorderVerticalSymbolDefinitions(),
				$_highLightFieldCoordinate
			)
		);
	}


	// Class Methods

	/**
	 * Returns the border symbol definition for the vertical border parts.
	 *
	 * @return BorderSymbolDefinition The border symbol definition for the vertical border parts
	 */
	private function getBorderVerticalSymbolDefinitions(): BorderSymbolDefinition
	{
		return new BorderSymbolDefinition(
			"│",
			"│",
			"│",
			array(

				// Left and right start
				new CollisionSymbolDefinition(
					"╤",
					array(
						new CollisionDirection(array("left", "right"))
					),
					"start"
				),

				// Left and right center
				new CollisionSymbolDefinition(
					"┼",
					array(
						new CollisionDirection(array("left", "right"))
					),
					"center"
				),

				// Left and right bottom
				new CollisionSymbolDefinition(
					"╧",
					array(
						new CollisionDirection(array("left", "right"))
					),
					"bottom"
				)
			)
		);
	}

	/**
	 * Returns the border symbol definition for the horizontal border parts.
	 *
	 * @return BorderSymbolDefinition The border symbol definition for the horizontal border parts
	 */
	private function getBorderHorizontalSymbolDefinition(): BorderSymbolDefinition
	{
		return new BorderSymbolDefinition(
			"─",
			"─",
			"─",
			array(

				// Top and bottom start
				new CollisionSymbolDefinition(
					"╟",
					array(
						new CollisionDirection(array("top", "bottom"))
					),
					"start"
				),

				// Top and bottom center
				new CollisionSymbolDefinition(
					"┼",
					array(
						new CollisionDirection(array("top", "bottom"))
					),
					"center"
				),

				// Top and bottom bottom
				new CollisionSymbolDefinition(
					"╢",
					array(
						new CollisionDirection(array("top", "bottom"))
					),
					"bottom"
				)
			)
		);
	}

	public function addBordersToRowString(String $_rowOutputString, int $_y): String
	{
		// TODO: This string must be added right to the outer border
		// TODO: additionally two symbols must be added to the left of the board to move it back to the center
		$rowOutputString = $_rowOutputString;

		if ($this->highLightFieldCoordinate && $_y == $this->highLightFieldCoordinate->y())
		{
			$rowOutputString .= " " . $_y;
		}

		return parent::addBordersToRowString($rowOutputString, $_y);
	}

	private function getXCoordinateHighLightString(Board $_board)
	{
		// TODO: This string must be added above the outer border of the board
		$hasInnerLeftBorder = $this->hasLeftBorder();
		$hasInnerRightBorder = $this->hasRightBorder();

		$paddingLeftLength = $this->highLightFieldCoordinate->x() + (int)$hasInnerLeftBorder;
		$paddingTotalLength = $_board->width() + (int)$hasInnerLeftBorder + (int)$hasInnerRightBorder;

		// Output the X-Coordinate of the highlighted cell above the board
		$paddingLeftString = str_repeat(" ", $paddingLeftLength);
		$xCoordinateHighLightString = str_pad(
			$paddingLeftString . $this->highLightFieldCoordinate->x(),
			$paddingTotalLength
		);

		return $xCoordinateHighLightString . "\n";
	}
}
