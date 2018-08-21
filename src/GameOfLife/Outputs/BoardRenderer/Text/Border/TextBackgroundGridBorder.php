<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Text\Border\BorderPart\CollisionDirection;
use BoardRenderer\Text\Border\Shapes\TextGridBorderShape;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use BoardRenderer\Text\Border\SymbolDefinition\CollisionSymbolDefinition;

/**
 * The background grid border for texts.
 */
class TextBackgroundGridBorder extends BaseBorder
{
	// Magic Methods

	/**
	 * TextBackgroundGridBorder constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border of this border
	 */
	public function __construct(BaseBorder $_parentBorder = null)
	{
		parent::__construct(
			$_parentBorder,
			new TextGridBorderShape(
				$this,
				new BorderPartThickness(1, 1),
				new BorderPartThickness(1, 1),
				$this->getBorderHorizontalSymbolDefinition(),
				$this->getBorderVerticalSymbolDefinitions()
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
					new CollisionDirection(array("left", "right")),
					"start"
				),

				// Left and right center
				new CollisionSymbolDefinition(
					"┼",
					new CollisionDirection(array("left", "right")),
					"center"
				),

				// Left and right bottom
				new CollisionSymbolDefinition(
					"╧",
					new CollisionDirection(array("left", "right")),
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
					new CollisionDirection(array("top", "bottom")),
					"start"
				),

				// Top and bottom center
				new CollisionSymbolDefinition(
					"┼",
					new CollisionDirection(array("top", "bottom")),
					"center"
				),

				// Top and bottom bottom
				new CollisionSymbolDefinition(
					"╢",
					new CollisionDirection(array("top", "bottom")),
					"bottom"
				)
			)
		);
	}
}
