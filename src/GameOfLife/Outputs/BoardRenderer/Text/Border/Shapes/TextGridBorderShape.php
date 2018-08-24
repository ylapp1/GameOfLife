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
use BoardRenderer\Base\Border\Shapes\BaseGridBorderShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextVerticalCollisionBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use GameOfLife\Coordinate;

/**
 * The text border shape for background grids.
 */
class TextGridBorderShape extends BaseGridBorderShape
{
	// Attributes

	/**
	 * The border symbol definition for the horizontal border parts
	 *
	 * @var BorderSymbolDefinition $borderHorizontalSymbolDefinition
	 */
	private $borderHorizontalSymbolDefinition;

	/**
	 * The border symbol definition for the vertical border parts
	 *
	 * @var BorderSymbolDefinition $borderVerticalSymbolDefinition
	 */
	private $borderVerticalSymbolDefinition;


	// Magic Methods

	/**
	 * TextGridBorderShape constructor.
	 *
	 * @param BaseBorder $_parentBorder
	 * @param BorderPartThickness $_horizontalThickness
	 * @param BorderPartThickness $_verticalThickness
	 * @param BorderSymbolDefinition $_borderHorizontalSymbolDefinition
	 * @param BorderSymbolDefinition $_borderVerticalSymbolDefinition
	 */
	public function __construct(BaseBorder $_parentBorder, BorderPartThickness $_horizontalThickness, BorderPartThickness $_verticalThickness, BorderSymbolDefinition $_borderHorizontalSymbolDefinition, BorderSymbolDefinition $_borderVerticalSymbolDefinition)
	{
		parent::__construct($_parentBorder, $_horizontalThickness, $_verticalThickness);
		$this->borderHorizontalSymbolDefinition = $_borderHorizontalSymbolDefinition;
		$this->borderVerticalSymbolDefinition = $_borderVerticalSymbolDefinition;
	}


	// Class Methods

	/**
	 * Returns a horizontal border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 *
	 * @return TextBorderPart The horizontal border part
	 */
	protected function getHorizontalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt)
	{
		return new TextBorderPart(
			$this->parentBorder,
			$_startsAt,
			$_endsAt,
			new TextHorizontalBorderPartShape(),
			$this->horizontalBorderPartsThickness,
			$this->borderHorizontalSymbolDefinition
		);
	}

	/**
	 * Returns a vertical border part for the background grid.
	 *
	 * @param Coordinate $_startsAt The start position
	 * @param Coordinate $_endsAt The end position
	 *
	 * @return TextBorderPart The vertical border part
	 */
	protected function getVerticalBackgroundGridBorderPart(Coordinate $_startsAt, Coordinate $_endsAt)
	{
		return new TextBorderPart(
			$this->parentBorder,
			$_startsAt,
			$_endsAt,
			new TextVerticalCollisionBorderPartShape(),
			$this->verticalBorderPartsThickness,
			$this->borderVerticalSymbolDefinition
		);
	}
}
