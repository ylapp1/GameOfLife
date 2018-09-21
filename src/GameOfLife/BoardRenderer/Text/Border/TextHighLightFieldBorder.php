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
use Utils\Geometry\Coordinate;

/**
 * Creates border parts that highlight a single field.
 */
class TextHighLightFieldBorder extends BaseBorder
{
	// Magic Methods

	/**
	 * TextHighLightFieldBorder constructor.
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
				new CollisionSymbolDefinition(
					"┼",
					array(
						new CollisionDirection(array("left", "right"))
					),
					array("center")
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
				new CollisionSymbolDefinition(
					"┼",
					array(
						new CollisionDirection(array("top", "bottom"))
					),
					array("center")
				)
			)
		);
	}
}
