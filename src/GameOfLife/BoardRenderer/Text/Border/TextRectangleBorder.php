<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border;

use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Text\Border\Shapes\TextRectangleBorderShape;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use BoardRenderer\Text\Border\SymbolDefinition\CollisionSymbolDefinition;
use Util\Geometry\Rectangle;

/**
 * Parent class for text rectangle borders.
 */
abstract class TextRectangleBorder extends BaseBorder
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
	 * BaseRectangleBorder constructor.
	 *
	 * @param BaseBorder $_parentBorder The parent border
	 * @param Rectangle $_rectangle The rectangle of this border
	 * @param BorderPartThickness $_horizontalThickness The thickness for horizontal border parts of this border
	 * @param BorderPartThickness $_verticalThickness The thickness for vertical border parts of this border
	 * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
	 * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
	 * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
	 * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
	 * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
	 * @param String $_borderSymbolLeftRight The symbol for the left an right border
	 */
	public function __construct($_parentBorder = null, Rectangle $_rectangle, BorderPartThickness $_horizontalThickness, BorderPartThickness $_verticalThickness, String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight)
	{
		$this->borderSymbolTopLeft = $_borderSymbolTopLeft;
		$this->borderSymbolTopRight = $_borderSymbolTopRight;
		$this->borderSymbolBottomLeft = $_borderSymbolBottomLeft;
		$this->borderSymbolBottomRight = $_borderSymbolBottomRight;
		$this->borderSymbolTopBottom = $_borderSymbolTopBottom;
		$this->borderSymbolLeftRight = $_borderSymbolLeftRight;

		parent::__construct(
			$_parentBorder,
			new TextRectangleBorderShape(
				$this,
				$_rectangle,
				$_horizontalThickness,
				$_verticalThickness,
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
			$this->getBorderTopCollisionSymbolDefinitions()
		);
	}

	/**
	 * Returns the collision symbol definitions for the top border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the top border
	 */
	abstract protected function getBorderTopCollisionSymbolDefinitions(): array;

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
			$this->getBorderBottomCollisionSymbolDefinitions()
		);
	}

	/**
	 * Returns the collision symbol definitions for the bottom border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the bottom border
	 */
	abstract protected function getBorderBottomCollisionSymbolDefinitions(): array;

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
			$this->getBorderLeftCollisionSymbolDefinitions()
		);
	}

	/**
	 * Returns the collision symbol definitions for the left border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the left border
	 */
	abstract protected function getBorderLeftCollisionSymbolDefinitions(): array;

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
			$this->getBorderRightCollisionSymbolDefinitions()
		);
	}

	/**
	 * Returns the collision symbol definitions for the right border.
	 *
	 * @return CollisionSymbolDefinition[] The collision symbol definitions for the right border
	 */
	abstract protected function getBorderRightCollisionSymbolDefinitions(): array;
}
