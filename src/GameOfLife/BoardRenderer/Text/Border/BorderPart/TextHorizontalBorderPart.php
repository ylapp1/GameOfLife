<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\Shapes\BaseBorderShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use GameOfLife\Coordinate;

/**
 * Horizontal border part for TextBoardRenderer classes.
 */
class TextHorizontalBorderPart extends TextBorderPart
{
	// Magic Methods

	/**
	 * TextHorizontalBorderPart constructor.
	 *
	 * @param BaseBorderShape $_parentBorderShape The parent border of this border part
	 * @param Coordinate $_startsAt The start coordinate of this border
	 * @param Coordinate $_endsAt The end coordinate of this border
	 * @param BorderPartThickness $_thickness The thickness of this border part
	 * @param BorderSymbolDefinition $_borderSymbolDefinition The border symbol definition
	 */
	public function __construct($_parentBorderShape, Coordinate $_startsAt, Coordinate $_endsAt, BorderPartThickness $_thickness, BorderSymbolDefinition $_borderSymbolDefinition)
	{
		parent::__construct($_parentBorderShape, $_startsAt, $_endsAt, new TextHorizontalBorderPartShape($this), $_thickness, $_borderSymbolDefinition);
	}
}