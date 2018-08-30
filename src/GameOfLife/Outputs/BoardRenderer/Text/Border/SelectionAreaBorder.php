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
use BoardRenderer\Text\Border\SymbolDefinition\CollisionSymbolDefinition;
use GameOfLife\Rectangle;

/**
 * Prints the borders for selection areas inside boards.
 */
class SelectionAreaBorder extends TextRectangleBorder
{
    // Magic Methods

	/**
	 * SelectionAreaBorder constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border
	 * @param Rectangle $_rectangle The selection are rectangle
	 */
	public function __construct($_parentBorder, Rectangle $_rectangle)
	{
		parent::__construct(
			$_parentBorder,
			$_rectangle,
			"┏",
			"┓",
			"┗",
			"┛",
			"╍",
			"┋"
		);
	}


	// Class Methods

	/**
	 * Returns the collision symbol definitions for the top border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the top border
	 */
	protected function getBorderTopCollisionSymbolDefinitions(): array
	{
		return array(
			// TODO: Add the collision symbols for all situations (left-right) (top-bottom)

			// Bottom start
			new CollisionSymbolDefinition(
				$this->borderSymbolTopLeft,
				array(
					new CollisionDirection(array("bottom"))
				),
				"start"
			),

			// Bottom center
			new CollisionSymbolDefinition(
				"╤",
				array(
					new CollisionDirection(array("bottom"))
				),
				"center"
			),

			// Bottom end
			new CollisionSymbolDefinition(
				$this->borderSymbolTopRight,
				array(
					new CollisionDirection(array("bottom"))
				),
				"end"
			)
		);
	}

	/**
	 * Returns the collision symbol definitions for the bottom border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the bottom border
	 */
	protected function getBorderBottomCollisionSymbolDefinitions(): array
	{
		return array(

			// TODO: Add the collision symbols for all situations (left-right) (top-bottom)

			// Top start
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomLeft,
				array(
					new CollisionDirection(array("top"))
				),
				"start"
			),

			// Top center
			new CollisionSymbolDefinition(
				"╧",
				array(
					new CollisionDirection(array("top"))
				),
				"center"
			),

			// Top end
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomRight,
				array(
					new CollisionDirection(array("top"))
				),
				"end"
			)
		);
	}

	/**
	 * Returns the collision symbol definitions for the left border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the left border
	 */
	protected function getBorderLeftCollisionSymbolDefinitions(): array
	{
		return array(

			// TODO: Add the collision symbols for all situations (left-right) (top-bottom)

			// Right start
			new CollisionSymbolDefinition(
				$this->borderSymbolTopLeft,
				array(
					new CollisionDirection(array("right"))
				),
				"start"
			),

			// Right center
			new CollisionSymbolDefinition(
				"╟",
				array(
					new CollisionDirection(array("right"))
				),
				"center"
			),

			// Right end
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomLeft,
				array(
					new CollisionDirection(array("right"))
				),
				"end"
			)
		);
	}

	/**
	 * Returns the collision symbol definitions for the right border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the right border
	 */
	protected function getBorderRightCollisionSymbolDefinitions(): array
	{
		// TODO: Add the collision symbols for all situations (left-right) (top-bottom)

		return array(
			// Left start
			new CollisionSymbolDefinition(
				$this->borderSymbolTopRight,
				array(
					new CollisionDirection(array("left"))
				),
				"start"
			),

			// Left center
			new CollisionSymbolDefinition(
				"╢",
				array(
					new CollisionDirection(array("left"))
				),
				"center"
			),

			// Left end
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomRight,
				array(
					new CollisionDirection(array("left"))
				),
				"end"
			)
		);
	}
}
