<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\Shapes;

use GameOfLife\Coordinate;
use GameOfLife\Rectangle;
use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Base\Border\Shapes\RectangleBorderShape;
use Output\BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use Output\BoardRenderer\Text\Border\BorderPart\Shapes\TextVerticalCollisionBorderPartShape;
use Output\BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use Output\BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;

/**
 * Creates border parts that form a rectangle.
 */
class TextRectangleBorderShape extends RectangleBorderShape
{
	// Attributes

	/**
	 * The symbol for the top left corner of the border
	 *
	 * @var String $borderSymbolTopLeft
	 */
	protected $borderSymbolTopLeft;

	/**
	 * The symbol for the top right corner of the border
	 *
	 * @var String $borderSymbolTopRight
	 */
	protected $borderSymbolTopRight;

	/**
	 * The symbol for the bottom left corner of the border
	 *
	 * @var String $borderSymbolBottomLeft
	 */
	protected $borderSymbolBottomLeft;

	/**
	 * The symbol for the bottom right corner of the border
	 *
	 * @var String $borderSymbolBottomRight
	 */
	protected $borderSymbolBottomRight;

	/**
	 * The symbol for the top and bottom border
	 *
	 * @var String $borderSymbolTopBottom
	 */
	protected $borderSymbolTopBottom;

	/**
	 * The symbol for the left an right border
	 *
	 * @var String $borderSymbolLeftRight
	 */
	protected $borderSymbolLeftRight;


	// Magic Methods

    /**
     * BaseBorderPrinter constructor.
     *
     * @param BaseBorder $_parentBorder The parent border
     * @param Rectangle $_rectangle The rectangle
     * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
     * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
     * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
     * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
     * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
     * @param String $_borderSymbolLeftRight The symbol for the left an right border
     */
	public function __construct($_parentBorder, Rectangle $_rectangle, String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
	{
	    parent::__construct($_parentBorder, $_rectangle);
		$this->borderSymbolTopLeft = $_borderSymbolTopLeft;
		$this->borderSymbolTopRight = $_borderSymbolTopRight;
		$this->borderSymbolBottomLeft = $_borderSymbolBottomLeft;
		$this->borderSymbolBottomRight = $_borderSymbolBottomRight;
		$this->borderSymbolTopBottom = $_borderSymbolTopBottom;
		$this->borderSymbolLeftRight = $_borderSymbolLeftRight;

		// TODO: Add collision symbols for all border parts to this class
	}


	// Class Methods

    /**
     * Generates and returns the top border part of this border shape.
     *
     * @return TextBorderPart The top border part of this border shape
     */
	protected function getTopBorderPart(): TextBorderPart
    {
        $startsAt = clone $this->rectangle->topLeftCornerCoordinate();
        $endsAt = new Coordinate(
            $this->rectangle->bottomRightCornerCoordinate()->x() + 1,
            $this->rectangle->topLeftCornerCoordinate()->y()
        );

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextHorizontalBorderPartShape(),
	        new BorderSymbolDefinition($this->borderSymbolTopLeft, $this->borderSymbolTopBottom, $this->borderSymbolTopRight)
        );
    }

    /**
     * Generates and returns the bottom border part of this border shape.
     *
     * @return TextBorderPart The bottom border part of this border shape
     */
    protected function getBottomBorderPart(): TextBorderPart
    {
        $startsAt = new Coordinate(
            $this->rectangle->topLeftCornerCoordinate()->x(),
            $this->rectangle->bottomRightCornerCoordinate()->y() + 1
        );
        $endsAt = new Coordinate(
        	$this->rectangle->bottomRightCornerCoordinate()->x() + 1,
	        $this->rectangle->bottomRightCornerCoordinate()->y() + 1
        );

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextHorizontalBorderPartShape(),
            new BorderSymbolDefinition($this->borderSymbolBottomLeft, $this->borderSymbolTopBottom, $this->borderSymbolBottomRight)
        );
    }

    /**
     * Generates and returns the left border part of this border shape.
     *
     * @return TextBorderPart The left border part of this border shape
     */
    protected function getLeftBorderPart(): TextBorderPart
    {
        $startsAt = clone $this->rectangle->topLeftCornerCoordinate();
        $endsAt = new Coordinate(
            $this->rectangle->topLeftCornerCoordinate()->x(),
            $this->rectangle->bottomRightCornerCoordinate()->y() + 1
        );

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextVerticalCollisionBorderPartShape(),
            new BorderSymbolDefinition($this->borderSymbolTopLeft, $this->borderSymbolLeftRight, $this->borderSymbolBottomLeft)
        );
    }

    /**
     * Generates and returns the right border part of this border shape.
     *
     * @return TextBorderPart The right border part of this border shape
     */
    protected function getRightBorderPart(): TextBorderPart
    {
        $startsAt = new Coordinate(
            $this->rectangle->bottomRightCornerCoordinate()->x() + 1,
            $this->rectangle->topLeftCornerCoordinate()->y()
        );
	    $endsAt = new Coordinate(
		    $this->rectangle->bottomRightCornerCoordinate()->x() + 1,
		    $this->rectangle->bottomRightCornerCoordinate()->y() + 1
	    );

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextVerticalCollisionBorderPartShape(),
            new BorderSymbolDefinition($this->borderSymbolTopRight, $this->borderSymbolLeftRight, $this->borderSymbolBottomRight)
        );
    }
}
