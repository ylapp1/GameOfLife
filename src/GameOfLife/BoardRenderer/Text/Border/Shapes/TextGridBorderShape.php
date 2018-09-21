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
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextHorizontalBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextVerticalBorderPart;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use Util\Geometry\Coordinate;

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
	 * @param BaseBorder $_parentBorder The parent border
	 * @param BorderPartThickness $_horizontalThickness The thickness of horizontal border parts
	 * @param BorderPartThickness $_verticalThickness The thickness of vertical border parts
	 * @param BorderSymbolDefinition $_borderHorizontalSymbolDefinition The border symbol definition for horizontal border parts
	 * @param BorderSymbolDefinition $_borderVerticalSymbolDefinition The border symbol definition for vertical border parts
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
		return new TextHorizontalBorderPart(
			$this,
			$_startsAt,
			$_endsAt,
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
		return new TextVerticalBorderPart(
			$this,
			$_startsAt,
			$_endsAt,
			$this->verticalBorderPartsThickness,
			$this->borderVerticalSymbolDefinition
		);
	}
}
