<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;
use GameOfLife\Coordinate;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextVerticalCollisionBorderPartShape;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;

/**
 * Container that stores the information about a part of a border.
 * This class uses text symbols to render the border part.
 */
class TextBorderPart extends BorderPart
{
    // Attributes

	/**
	 * The shape of this border part
	 *
	 * @var TextHorizontalBorderPartShape|TextVerticalCollisionBorderPartShape
	 */
	protected $shape;

	/**
	 * The border symbol definition
	 *
	 * @var BorderSymbolDefinition $borderSymbolDefinition
	 */
	protected $borderSymbolDefinition;


    // Magic Methods

    /**
     * TextBorderPart constructor.
     *
     * @param BaseBorder $_parentBorder The parent border of this border part
     * @param Coordinate $_startsAt The start coordinate of this border
     * @param Coordinate $_endsAt The end coordinate of this border
     * @param BaseBorderPartShape $_shape The shape of this border part
     * @param BorderPartThickness $_thickness The thickness of this border part
     * @param BorderSymbolDefinition $_borderSymbolDefinition The border symbol definition
     */
    public function __construct($_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, $_shape, BorderPartThickness $_thickness, BorderSymbolDefinition $_borderSymbolDefinition)
    {
        parent::__construct($_parentBorder, $_startsAt, $_endsAt, $_shape, $_thickness);

        $this->borderSymbolDefinition = $_borderSymbolDefinition;
    }


    // Getters and Setters

	/**
	 * Returns the border symbol definition.
	 *
	 * @return BorderSymbolDefinition The border symbol definition
	 */
	public function borderSymbolDefinition(): BorderSymbolDefinition
	{
		return $this->borderSymbolDefinition;
	}


    // Class Methods

    /**
     * Returns the symbols that will be used to print this border part.
     *
     * @return String[] The symbols that will be used to print this border part
     */
    public function getBorderSymbols(): array
    {
        $borderSymbols = $this->renderBorderPart();
	    $borderSymbols = $this->renderCollisions($borderSymbols);

	    return $borderSymbols;
    }

    /**
     * Returns the border symbols without collision symbols.
     *
     * @return String[] The border symbols without collision symbols
     */
    private function renderBorderPart(): array
    {
        $borderSymbols = array();

        $borderSymbols[] = $this->borderSymbolDefinition->startSymbol();
        for ($i = 1; $i <= $this->shape->getNumberOfBorderSymbols(); $i++)
        {
            $borderSymbols[$i] = $this->borderSymbolDefinition->centerSymbol();
        }
        $borderSymbols[] = $this->borderSymbolDefinition->endSymbol();

        return $borderSymbols;
    }

    /**
     * Adds the collision symbols to a list of border symbols and returns the updated list.
     *
     * @param String[] $_borderSymbols The list of border symbols
     *
     * @return String[] The updated list of border symbols
     */
    private function renderCollisions(array $_borderSymbols): array
    {
	    foreach ($this->ownCollisions as $collision)
        {
        	// Find dominating border
            if ($collision->isOuterBorderPartCollision()) $dominatingBorderPart = $collision->with();
            else $dominatingBorderPart = $this;

	        // Find collision symbol definition
	        $borderSymbolDefinition = $dominatingBorderPart->borderSymbolDefinition();
	        $collisionSymbol = null;

	        /** @var TextBorderPartCollisionPosition $collisionPosition */
	        $collisionPosition = $collision->position();

	        // Find default collision symbol
	        $defaultCollisionSymbol = " ";
	        if ($collisionPosition->isStartPosition()) $defaultCollisionSymbol = $borderSymbolDefinition->startSymbol();
	        elseif ($collisionPosition->isCenterPosition()) $defaultCollisionSymbol = $borderSymbolDefinition->centerSymbol();
	        elseif ($collisionPosition->isEndPosition()) $defaultCollisionSymbol = $borderSymbolDefinition->endSymbol();

	        // Find direction specific collision symbol
	        foreach ($borderSymbolDefinition->collisionSymbolDefinitions() as $collisionSymbolDefinition)
	        {
	        	if ($collisionPosition->isStartPosition() && $collisionSymbolDefinition->isStartPosition() ||
			        $collisionPosition->isCenterPosition() && $collisionSymbolDefinition->isCenterPosition() ||
			        $collisionPosition->isEndPosition() && $collisionSymbolDefinition->isEndPosition())
		        { // The collision position matches

			        if ($collisionPosition->collisionDirection()->equals($collisionSymbolDefinition->collisionDirection()))
			        { // The collision direction matches
				        $collisionSymbol = $collisionSymbolDefinition->collisionSymbol();
				        break;
			        }
		        }
	        }

            if (! $collisionSymbol) $collisionSymbol = $defaultCollisionSymbol;

            $borderSymbolPosition = $this->shape->getBorderSymbolPositionOf($collision->position());
            $_borderSymbols[$borderSymbolPosition] = $collisionSymbol;
        }

        return $_borderSymbols;
    }
}
