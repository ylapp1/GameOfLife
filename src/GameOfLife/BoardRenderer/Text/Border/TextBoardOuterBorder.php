<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border;

use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Text\Border\BorderPart\CollisionDirection;
use BoardRenderer\Text\Border\SymbolDefinition\CollisionSymbolDefinition;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use GameOfLife\Rectangle;

/**
 * Generates border strings for boards.
 */
class TextBoardOuterBorder extends TextRectangleBorder
{
    // Magic Methods

    /**
     * TextBoardOuterBorder constructor.
     *
     * @param Board $_board The board for which the outer border will be created
     */
    public function __construct(Board $_board)
    {
        $topLeftCornerCoordinate = new Coordinate(0, 0);
        $bottomRightCornerCoordinate = new Coordinate($_board->width() - 1, $_board->height() - 1);
        $rectangle = new Rectangle($topLeftCornerCoordinate, $bottomRightCornerCoordinate);

        parent::__construct(
        	null,
	        $rectangle,
	        new BorderPartThickness(1, 1),
	        new BorderPartThickness(1, 1),
	        "╔",
	        "╗",
	        "╚",
	        "╝",
	        "═",
	        "║"
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
			// Start
			new CollisionSymbolDefinition(
				$this->borderSymbolTopLeft,
				array(
					new CollisionDirection(array("bottom")),
					new CollisionDirection(array("right"))
				),
				array("start")
			),

			// Center
			new CollisionSymbolDefinition(
				"╤",
				array(
					new CollisionDirection(array("bottom"))
				),
				array("center")
			),
			new CollisionSymbolDefinition(
				$this->borderSymbolTopBottom,
				array(
					new CollisionDirection(array("left")),
					new CollisionDirection(array("right")),
					new CollisionDirection(array("left", "right"))
				),
				array("center"),
				true,
				false
			),

			// End
			new CollisionSymbolDefinition(
				$this->borderSymbolTopRight,
				array(
					new CollisionDirection(array("bottom")),
					new CollisionDirection(array("left"))
				),
				array("end")
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
			// Start
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomLeft,
				array(
					new CollisionDirection(array("top")),
					new CollisionDirection(array("right"))
				),
				array("start")
			),

			// Center
			new CollisionSymbolDefinition(
				"╧",
				array(
					new CollisionDirection(array("top"))
				),
				array("center")
			),
			new CollisionSymbolDefinition(
				$this->borderSymbolTopBottom,
				array(
					new CollisionDirection(array("left")),
					new CollisionDirection(array("right")),
					new CollisionDirection(array("left", "right"))
				),
				array("center"),
				true,
				false
			),

			// End
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomRight,
				array(
					new CollisionDirection(array("top")),
					new CollisionDirection(array("left"))
				),
				array("end")
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
			// Right start
			new CollisionSymbolDefinition(
				$this->borderSymbolTopLeft,
				array(
					new CollisionDirection(array("right")),
					new CollisionDirection(array("bottom"))
				),
				array("start")
			),

			// Right center
			new CollisionSymbolDefinition(
				"╟",
				array(
					new CollisionDirection(array("right"))
				),
				array("center")
			),
			new CollisionSymbolDefinition(
				$this->borderSymbolLeftRight,
				array(
					new CollisionDirection(array("top")),
					new CollisionDirection(array("bottom")),
					new CollisionDirection(array("top", "bottom"))
				),
				array("center"),
				false,
				true
			),

			// Right end
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomLeft,
				array(
					new CollisionDirection(array("right")),
					new CollisionDirection(array("top"))
				),
				array("end")
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
		return array(
			// Left start
			new CollisionSymbolDefinition(
				$this->borderSymbolTopRight,
				array(
					new CollisionDirection(array("left")),
					new CollisionDirection(array("bottom"))
				),
				array("start")
			),

			// Left center
			new CollisionSymbolDefinition(
				"╢",
				array(
					new CollisionDirection(array("left"))
				),
				array("center")
			),
			new CollisionSymbolDefinition(
				$this->borderSymbolLeftRight,
				array(
					new CollisionDirection(array("top")),
					new CollisionDirection(array("bottom")),
					new CollisionDirection(array("top", "bottom"))
				),
				array("center"),
				false,
				true
			),

			// Left end
			new CollisionSymbolDefinition(
				$this->borderSymbolBottomRight,
				array(
					new CollisionDirection(array("left")),
					new CollisionDirection(array("top"))
				),
				array("end")
			)
		);
	}
}
