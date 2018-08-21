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
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use BoardRenderer\Text\Border\SymbolDefinition\CollisionSymbolDefinition;
use GameOfLife\Board;
use GameOfLife\Coordinate;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\Border\Shapes\TextRectangleBorderShape;
use GameOfLife\Rectangle;

/**
 * Generates border strings for boards.
 */
class BoardOuterBorder extends BaseBorder
{
	/**
	 * The symbol for the top left corner of the border
	 *
	 * @var String $borderSymbolTopLeft
	 */
	private $borderSymbolTopLeft = "╔";

	/**
	 * The symbol for the top right corner of the border
	 *
	 * @var String $borderSymbolTopRight
	 */
	private $borderSymbolTopRight = "╗";

	/**
	 * The symbol for the bottom left corner of the border
	 *
	 * @var String $borderSymbolBottomLeft
	 */
	protected $borderSymbolBottomLeft = "╚";

	/**
	 * The symbol for the bottom right corner of the border
	 *
	 * @var String $borderSymbolBottomRight
	 */
	protected $borderSymbolBottomRight = "╝";

	/**
	 * The symbol for the top and bottom border
	 *
	 * @var String $borderSymbolTopBottom
	 */
	protected $borderSymbolTopBottom = "═";

	/**
	 * The symbol for the left an right border
	 *
	 * @var String $borderSymbolLeftRight
	 */
	protected $borderSymbolLeftRight = "║";


    // Magic Methods

    /**
     * BoardOuterBorder constructor.
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
            new TextRectangleBorderShape(
                $this,
	            $rectangle,
                new BorderPartThickness(1, 1),
                new BorderPartThickness(1, 1),
                $this->getBorderTopSymbolDefinition(),
                $this->getBorderBottomSymbolDefinition(),
                $this->getBorderLeftSymbolDefinition(),
                $this->getBorderRightSymbolDefinition()
            )
        );
    }


    // Class Methods

	/**
	 * Returns the border symbol definition for the top border.
	 *
	 * @return BorderSymbolDefinition The border symbol definition for the top border
	 */
    private function getBorderTopSymbolDefinition(): BorderSymbolDefinition
    {
    	return new BorderSymbolDefinition(
    		$this->borderSymbolTopLeft,
    		$this->borderSymbolTopBottom,
    		$this->borderSymbolTopRight,
    		array(
	            // Bottom start
	            new CollisionSymbolDefinition(
	                $this->borderSymbolTopLeft,
				    new CollisionDirection(array("bottom")),
				    "start"
			    ),

			    // Bottom center
			    new CollisionSymbolDefinition(
			        "╤",
			        new CollisionDirection(array("bottom")),
				    "center"
			    ),

			    // Bottom end
			    new CollisionSymbolDefinition(
			        $this->borderSymbolTopRight,
				    new CollisionDirection(array("bottom")),
				    "end"
		        )
	        )
	    );
    }

	/**
	 * Returns the border symbol definition for the bottom border.
	 *
	 * @return BorderSymbolDefinition The border symbol definition for the bottom border
	 */
    private function getBorderBottomSymbolDefinition(): BorderSymbolDefinition
    {
	    return new BorderSymbolDefinition(
	    	$this->borderSymbolBottomLeft,
	    	$this->borderSymbolTopBottom,
	    	$this->borderSymbolBottomRight,
	    	array(
			    // Top start
			    new CollisionSymbolDefinition(
				    $this->borderSymbolBottomLeft,
				    new CollisionDirection(array("top")),
				    "start"
			    ),

			    // Top center
			    new CollisionSymbolDefinition(
				    "╧",
				    new CollisionDirection(array("top")),
				    "center"
			    ),

			    // Top end
			    new CollisionSymbolDefinition(
				    $this->borderSymbolBottomRight,
				    new CollisionDirection(array("top")),
				    "end"
			    )
		    )
	    );
    }

	/**
	 * Returns the border symbol definition for the left border.
	 *
	 * @return BorderSymbolDefinition The border symbol definition for the left border
	 */
	private function getBorderLeftSymbolDefinition(): BorderSymbolDefinition
	{
		return new BorderSymbolDefinition(
			$this->borderSymbolTopLeft,
			$this->borderSymbolLeftRight,
			$this->borderSymbolBottomLeft,
			array(
				// Right start
				new CollisionSymbolDefinition(
					$this->borderSymbolTopLeft,
					new CollisionDirection(array("right")),
					"start"
				),

				// Right center
				new CollisionSymbolDefinition(
					"╟",
					new CollisionDirection(array("right")),
					"center"
				),

				// Right end
				new CollisionSymbolDefinition(
					$this->borderSymbolBottomLeft,
					new CollisionDirection(array("right")),
					"end"
				)
			)
		);
	}

	/**
	 * Returns the border symbol definition for the right border.
	 *
	 * @return BorderSymbolDefinition The border symbol definition for the right border
	 */
	private function getBorderRightSymbolDefinition(): BorderSymbolDefinition
	{
		return new BorderSymbolDefinition(
			$this->borderSymbolTopRight,
			$this->borderSymbolLeftRight,
			$this->borderSymbolBottomRight,
			array(
				// Left start
				new CollisionSymbolDefinition(
					$this->borderSymbolTopRight,
					new CollisionDirection(array("left")),
					"start"
				),

				// Left center
				new CollisionSymbolDefinition(
					"╢",
					new CollisionDirection(array("left")),
					"center"
				),

				// Left end
				new CollisionSymbolDefinition(
					$this->borderSymbolBottomRight,
					new CollisionDirection(array("left")),
					"end"
				)
			)
		);
	}
}
