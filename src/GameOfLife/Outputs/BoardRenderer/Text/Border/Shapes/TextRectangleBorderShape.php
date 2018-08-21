<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use GameOfLife\Coordinate;
use GameOfLife\Rectangle;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\Shapes\RectangleBorderShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextVerticalCollisionBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;

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
	protected $borderTopSymbolDefinition;

	protected $borderBottomSymbolDefinition;

	protected $borderLeftSymbolDefinition;

	/**
	 * The symbol for the top right corner of the border
	 *
	 * @var String $borderSymbolTopRight
	 */
	protected $borderRightSymbolDefinition;


	// Magic Methods

	/**
	 * BaseBorderPrinter constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border
	 * @param Rectangle $_rectangle The rectangle
	 * @param BorderPartThickness $_horizontalThickness
	 * @param BorderPartThickness $_verticalThickness
	 * @param BorderSymbolDefinition $_borderTopSymbolDefinition
	 * @param BorderSymbolDefinition $_borderBottomSymbolDefinition
	 * @param BorderSymbolDefinition $_borderLeftSymbolDefinition
	 * @param BorderSymbolDefinition $_borderRightSymbolDefinition
	 */
	public function __construct($_parentBorder, Rectangle $_rectangle, BorderPartThickness $_horizontalThickness, BorderPartThickness $_verticalThickness, BorderSymbolDefinition $_borderTopSymbolDefinition, BorderSymbolDefinition $_borderBottomSymbolDefinition, BorderSymbolDefinition $_borderLeftSymbolDefinition, BorderSymbolDefinition $_borderRightSymbolDefinition)
	{
	    parent::__construct($_parentBorder, $_rectangle, $_horizontalThickness, $_verticalThickness);
		$this->borderTopSymbolDefinition = $_borderTopSymbolDefinition;
		$this->borderBottomSymbolDefinition = $_borderBottomSymbolDefinition;
		$this->borderLeftSymbolDefinition = $_borderLeftSymbolDefinition;
		$this->borderRightSymbolDefinition = $_borderRightSymbolDefinition;
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
	        $this->horizontalThickness,
	        $this->borderTopSymbolDefinition
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
	        $this->horizontalThickness,
	        $this->borderBottomSymbolDefinition
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
	        $this->verticalThickness,
            $this->borderLeftSymbolDefinition
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
	        $this->verticalThickness,
	        $this->borderRightSymbolDefinition
        );
    }
}
