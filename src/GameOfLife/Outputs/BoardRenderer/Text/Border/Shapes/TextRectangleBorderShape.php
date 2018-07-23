<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Base\Border\Shapes\RectangleBorderShape;
use Output\BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use Output\BoardRenderer\Text\BorderPart\TextBorderPart;

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
     * @param Coordinate $_topLeftCornerCoordinate The coordinate of the top left corner of the rectangle
     * @param Coordinate $_bottomRightCornerCoordinate The coordinate of the bottom right corner of the rectangle
     * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
     * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
     * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
     * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
     * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
     * @param String $_borderSymbolLeftRight The symbol for the left an right border
     */
	public function __construct($_parentBorder, Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate, String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
	{
	    parent::__construct($_parentBorder, $_topLeftCornerCoordinate, $_bottomRightCornerCoordinate);
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
        $startsAt = clone $this->topLeftCornerCoordinate;
        $endsAt = new Coordinate(
            $this->bottomRightCornerCoordinate->x(),
            $this->topLeftCornerCoordinate->y()
        );

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextHorizontalBorderPartShape(),
            $this->borderSymbolTopLeft,
            $this->borderSymbolTopBottom,
            $this->borderSymbolTopRight
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
            $this->topLeftCornerCoordinate->x(),
            $this->bottomRightCornerCoordinate->y()
        );
        $endsAt = clone $this->bottomRightCornerCoordinate;

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextHorizontalBorderPartShape(),
            $this->borderSymbolBottomLeft,
            $this->borderSymbolTopBottom,
            $this->borderSymbolBottomRight
        );
    }

    /**
     * Generates and returns the left border part of this border shape.
     *
     * @return TextBorderPart The left border part of this border shape
     */
    protected function getLeftBorderPart(): TextBorderPart
    {
        $startsAt = clone $this->topLeftCornerCoordinate;
        $endsAt = new Coordinate(
            $this->topLeftCornerCoordinate->x(),
            $this->bottomRightCornerCoordinate->y()
        );

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextHorizontalBorderPartShape(),
            $this->borderSymbolTopLeft,
            $this->borderSymbolLeftRight,
            $this->borderSymbolBottomLeft
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
            $this->bottomRightCornerCoordinate->x(),
            $this->topLeftCornerCoordinate->y()
        );
        $endsAt = clone $this->bottomRightCornerCoordinate;

        return new TextBorderPart(
            $this->parentBorder,
            $startsAt,
            $endsAt,
            new TextHorizontalBorderPartShape(),
            $this->borderSymbolTopRight,
            $this->borderSymbolLeftRight,
            $this->borderSymbolBottomRight
        );
    }
}
