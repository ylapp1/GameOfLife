<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\BorderPartCollision;
use BoardRenderer\Base\Border\BorderPart\BorderPartThickness;
use BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;
use BoardRenderer\Base\Border\Shapes\BaseBorderShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextBorderPartGridPosition;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\Shapes\TextVerticalBorderPartShape;
use BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;
use GameOfLife\Coordinate;

/**
 * Container that stores the information about a part of a border.
 * This class uses text symbols to render the border part.
 */
abstract class TextBorderPart extends BaseBorderPart
{
    // Attributes

	/**
	 * The shape of this border part
	 *
	 * @var TextHorizontalBorderPartShape|TextVerticalBorderPartShape
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
     * @param BaseBorderShape $_parentBorderShape The parent border of this border part
     * @param Coordinate $_startsAt The start coordinate of this border
     * @param Coordinate $_endsAt The end coordinate of this border
     * @param BaseBorderPartShape $_shape The shape of this border part
     * @param BorderPartThickness $_thickness The thickness of this border part
     * @param BorderSymbolDefinition $_borderSymbolDefinition The border symbol definition
     */
    public function __construct($_parentBorderShape, Coordinate $_startsAt, Coordinate $_endsAt, $_shape, BorderPartThickness $_thickness, BorderSymbolDefinition $_borderSymbolDefinition)
    {
    	$startsAt = new TextBorderPartGridPosition($_startsAt, false, false);
    	$endsAt = new TextBorderPartGridPosition($_endsAt, false, false);

        parent::__construct($_parentBorderShape, $startsAt, $endsAt, $_shape, $_thickness);

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
	    ksort($borderSymbols);

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
        for ($i = 0; $i < $this->shape->getNumberOfBorderSymbols(); $i++)
        {
            $borderSymbols[$i * 2 + 1] = $this->borderSymbolDefinition->centerSymbol();
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
    	$borderSymbols = $_borderSymbols;

        /*
         * The collisions are rendered in reverse order to make border parts that existed
         * first overwrite the collisions of border parts that were added later
         */
    	$borderPartCollisions = array_reverse($this->ownCollisions);

        /** @var BorderPartCollision $collision */
        foreach ($borderPartCollisions as $collision)
        {
        	// Find dominating border
            if ($collision->isOuterBorderPartCollision()) $dominatingBorderPart = $collision->with();
            else $dominatingBorderPart = $this;

	        /** @var TextBorderPartCollisionPosition $collisionPosition */
	        foreach ($collision->positions() as $collisionPosition)
	        {
		        // Find the collision symbol
		        $collisionSymbol = $this->getCollisionSymbol($dominatingBorderPart, $collisionPosition);
		        if (! $collisionSymbol)
		        { // No collision symbol found, check the related border parts for a collision symbol
		        	$collisionSymbol = $this->getCollisionSymbolFromRelatedBorderParts($collisionPosition);
		        }
		        if (! $collisionSymbol)
		        { // Still no collision symbol found, use the default one
		        	$collisionSymbol = $this->getDefaultCollisionSymbol($dominatingBorderPart, $collisionPosition);
		        }

		        $borderSymbolPosition = $this->shape->getBorderSymbolPositionOf($collisionPosition);
		        $borderSymbols[$borderSymbolPosition] = $collisionSymbol;
	        }
        }

        return $borderSymbols;
    }

	/**
	 * Returns the default collision symbol for a specific collision position inside a border part.
	 *
	 * @param TextBorderPart $_dominatingBorderPart The dominating border part whose collision symbol definition will be used to determine the border symbol
	 * @param TextBorderPartCollisionPosition $_collisionPosition The collision position
	 *
	 * @return String The default collision symbol
	 */
    protected function getDefaultCollisionSymbol(TextBorderPart $_dominatingBorderPart, TextBorderPartCollisionPosition $_collisionPosition): String
    {
	    $borderSymbolDefinition = $_dominatingBorderPart->borderSymbolDefinition();

	    $isStartPosition = $_dominatingBorderPart->startsAt()->equals($_collisionPosition);
	    $isEndPosition = $_dominatingBorderPart->endsAt()->equals($_collisionPosition);

	    if ($isStartPosition) $defaultCollisionSymbol = $borderSymbolDefinition->startSymbol();
	    elseif ($isEndPosition) $defaultCollisionSymbol = $borderSymbolDefinition->endSymbol();
	    else $defaultCollisionSymbol = $borderSymbolDefinition->centerSymbol();

	    return $defaultCollisionSymbol;
    }

	/**
	 * Returns the collision symbol for a specific position and with a specific collision direction.
	 *
	 * @param TextBorderPart $_dominatingBorderPart The dominating border part whose collision symbol definition will be used to determine the border symbol
	 * @param TextBorderPartCollisionPosition $_collisionPosition The collision position
	 *
	 * @return String|null The collision symbol or null if no collision symbol for the position/direction combination was defined
	 */
    protected function getCollisionSymbol(TextBorderPart $_dominatingBorderPart, TextBorderPartCollisionPosition $_collisionPosition)
    {
	    $borderSymbolDefinition = $_dominatingBorderPart->borderSymbolDefinition();

	    $isStartPosition = $_dominatingBorderPart->startsAt()->equals($_collisionPosition);
	    $isEndPosition = $_dominatingBorderPart->endsAt()->equals($_collisionPosition);
	    $isCenterPosition = (! $isStartPosition && ! $isEndPosition);

	    $collisionSymbol = null;

	    // Find direction specific collision symbol
	    foreach ($borderSymbolDefinition->collisionSymbolDefinitions() as $collisionSymbolDefinition)
	    {
		    if ($isStartPosition && $collisionSymbolDefinition->isStartPosition() ||
			    $isCenterPosition && $collisionSymbolDefinition->isCenterPosition() ||
			    $isEndPosition && $collisionSymbolDefinition->isEndPosition())
		    { // The collision position matches

			    $collisionSymbolDefinitionMatches = false;

			    foreach ($collisionSymbolDefinition->collisionDirections() as $collisionDirection)
			    {
				    if ($_collisionPosition->collisionDirection()->equals($collisionDirection))
				    { // The collision direction matches
					    $collisionSymbolDefinitionMatches = true;
					    break;
				    }
			    }

			    if ($collisionSymbolDefinitionMatches)
			    {
				    $collisionSymbol = $collisionSymbolDefinition->collisionSymbol();
				    break;
			    }
		    }
	    }

	    return $collisionSymbol;
    }

	/**
	 * Returns the collision symbol from another border part of the parent border if the border part has a collision at a specific position.
	 *
	 * @param TextBorderPartCollisionPosition $_collisionPosition The collision position
	 *
	 * @return String|null The collision symbol or null if no collision symbol for the position was defined
	 */
    protected function getCollisionSymbolFromRelatedBorderParts(TextBorderPartCollisionPosition $_collisionPosition)
    {
	    /** @var TextBorderPart $relatedBorderPart */
	    foreach ($this->parentBorderShape->parentBorder()->borderParts() as $relatedBorderPart)
	    {
		    if ($relatedBorderPart !== $this)
		    {
			    foreach ($relatedBorderPart->ownCollisions() as $relatedBorderPartCollision)
			    {
			    	/** @var TextBorderPartCollisionPosition $relatedBorderPartCollisionPosition */
				    foreach ($relatedBorderPartCollision->positions() as $relatedBorderPartCollisionPosition)
			    	{
			    		if ($relatedBorderPartCollisionPosition->equals($_collisionPosition))
			    		{
			    			if ($relatedBorderPart->isOuterBorderPart($relatedBorderPartCollision->with()))
						    {
						    	$dominatingBorderPart = $relatedBorderPartCollision->with();
						    }
			    			else $dominatingBorderPart = $relatedBorderPart;

			    			$collisionSymbol = $relatedBorderPart->getCollisionSymbol($dominatingBorderPart, $relatedBorderPartCollisionPosition);
			    			if ($collisionSymbol) return $collisionSymbol;
					    }
				    }
			    }
		    }
	    }

	    return null;
    }
}
