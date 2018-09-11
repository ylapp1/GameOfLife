<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\Shapes;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\Shapes\RectangleBorderShape;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextHorizontalBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextVerticalBorderPart;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use GameOfLife\Coordinate;
use GameOfLife\Rectangle;

/**
 * Creates border parts that form a rectangle.
 */
class TextRectangleBorderShape extends RectangleBorderShape
{
	// Attributes

	/**
	 * The border symbol definition for the top border
	 *
	 * @var BorderSymbolDefinition $borderTopSymbolDefinition
	 */
	protected $borderTopSymbolDefinition;

	/**
	 * The border symbol definition for the bottom border
	 *
	 * @var BorderSymbolDefinition $borderBottomSymbolDefinition
	 */
	protected $borderBottomSymbolDefinition;

	/**
	 * The border symbol definition for the left border
	 *
	 * @var BorderSymbolDefinition $borderLeftSymbolDefinition
	 */
	protected $borderLeftSymbolDefinition;

	/**
	 * The border symbol definition for the right border
	 *
	 * @var BorderSymbolDefinition $borderRightSymbolDefinition
	 */
	protected $borderRightSymbolDefinition;


	// Magic Methods

	/**
	 * BaseBorderPrinter constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border
	 * @param Rectangle $_rectangle The rectangle
	 * @param BorderPartThickness $_horizontalThickness The thickness for horizontal border parts of this border
	 * @param BorderPartThickness $_verticalThickness The thickness for vertical border parts of this border
	 * @param BorderSymbolDefinition $_borderTopSymbolDefinition The border symbol definition for the top border
	 * @param BorderSymbolDefinition $_borderBottomSymbolDefinition The border symbol definition for the bottom border
	 * @param BorderSymbolDefinition $_borderLeftSymbolDefinition The border symbol definition for the left border
	 * @param BorderSymbolDefinition $_borderRightSymbolDefinition The border symbol definition for the right border
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

        return new TextHorizontalBorderPart(
            $this,
            $startsAt,
            $endsAt,
	        $this->horizontalBorderPartsThickness,
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

        return new TextHorizontalBorderPart(
            $this,
            $startsAt,
            $endsAt,
	        $this->horizontalBorderPartsThickness,
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

        return new TextVerticalBorderPart(
            $this,
            $startsAt,
            $endsAt,
	        $this->verticalBorderPartsThickness,
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

        return new TextVerticalBorderPart(
            $this,
            $startsAt,
            $endsAt,
	        $this->verticalBorderPartsThickness,
	        $this->borderRightSymbolDefinition
        );
    }
}
