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
use BoardRenderer\Text\Border\SymbolDefinition\CollisionSymbolDefinition;

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

	/**
	 * The collision symbol definition for the start symbol position
	 *
	 * @var CollisionSymbolDefinition $startCollisionSymbolDefinition
	 */
	protected $startCollisionSymbolDefinition;

	/**
	 * The collision symbol definition for a center symbol position
	 *
	 * @var CollisionSymbolDefinition $centerCollisionSymbolDefinition
	 */
	protected $centerCollisionSymbolDefinition;

	/**
	 * The collision symbol definition for the end symbol position
	 *
	 * @var CollisionSymbolDefinition $endCollisionSymbolDefinition
	 */
	protected $endCollisionSymbolDefinition;


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
     * @param CollisionSymbolDefinition $_startCollisionSymbolDefinition The collision symbol definition for the start symbol position
     * @param CollisionSymbolDefinition $_centerCollisionSymbolDefinition The collision symbol definition for a center symbol position
     * @param CollisionSymbolDefinition $_endCollisionSymbolDefinition The collision symbol definition for the end symbol position
     */
    public function __construct($_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, $_shape, BorderPartThickness $_thickness, BorderSymbolDefinition $_borderSymbolDefinition, CollisionSymbolDefinition $_startCollisionSymbolDefinition = null, CollisionSymbolDefinition $_centerCollisionSymbolDefinition = null, CollisionSymbolDefinition $_endCollisionSymbolDefinition = null)
    {
        parent::__construct($_parentBorder, $_startsAt, $_endsAt, $_shape, $_thickness);

        $this->borderSymbolDefinition = $_borderSymbolDefinition;

        if ($_startCollisionSymbolDefinition) $this->startCollisionSymbolDefinition = $_startCollisionSymbolDefinition;
        else $this->startCollisionSymbolDefinition = new CollisionSymbolDefinition();

        if ($_centerCollisionSymbolDefinition) $this->centerCollisionSymbolDefinition = $_centerCollisionSymbolDefinition;
        else $this->centerCollisionSymbolDefinition = new CollisionSymbolDefinition();

        if ($_endCollisionSymbolDefinition) $this->endCollisionSymbolDefinition = $_endCollisionSymbolDefinition;
        else $this->endCollisionSymbolDefinition = new CollisionSymbolDefinition();
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

	/**
	 * Returns the collision symbol definition for the start symbol position.
	 *
	 * @return CollisionSymbolDefinition The collision symbol definition for the start symbol position
	 */
    public function startCollisionSymbolDefinition(): CollisionSymbolDefinition
    {
        return $this->startCollisionSymbolDefinition;
    }

	/**
	 * Returns the collision symbol definition for a center symbol position.
	 *
	 * @return CollisionSymbolDefinition The collision symbol definition for a center symbol position
	 */
    public function centerCollisionSymbolDefinition(): CollisionSymbolDefinition
    {
        return $this->centerCollisionSymbolDefinition;
    }

	/**
	 * Returns the collision symbol definition for the end symbol position.
	 *
	 * @return CollisionSymbolDefinition The collision symbol definition for the end symbol position
	 */
    public function endCollisionSymbolDefinition(): CollisionSymbolDefinition
    {
        return $this->endCollisionSymbolDefinition;
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
	    // TODO: Fix collision position edge and first/last symbol

	    foreach ($this->collisions as $collision)
        {
        	// Find dominating border
            if ($collision->isOuterBorderPartCollision()) $dominatingBorderPart = $collision->with();
            else $dominatingBorderPart = $this;

            // Find collision symbol definition
	        $collisionSymbolDefinition = null;
	        $defaultCollisionSymbol = null;

            if ($collision->position()->equals($dominatingBorderPart->startsAt()))
            {
            	$collisionSymbolDefinition = $dominatingBorderPart->startCollisionSymbolDefinition();
	            $defaultCollisionSymbol = $dominatingBorderPart->borderSymbolDefinition()->startSymbol();
            }
            elseif ($collision->position()->equals($dominatingBorderPart->endsAt()))
            {
            	$collisionSymbolDefinition = $dominatingBorderPart->endCollisionSymbolDefinition();
            	$defaultCollisionSymbol = $dominatingBorderPart->borderSymbolDefinition()->endSymbol();
            }
            else
            {
            	$collisionSymbolDefinition = $dominatingBorderPart->centerCollisionSymbolDefinition();
            	$defaultCollisionSymbol = $dominatingBorderPart->borderSymbolDefinition()->centerSymbol();
            }

            // Find collision symbol
	        // TODO: Find the real symbol
	        $collisionSymbol = $collisionSymbolDefinition->collisionFromTopSymbol();
            if (! $collisionSymbol) $collisionSymbol = $defaultCollisionSymbol;

            $borderSymbolPosition = $this->shape->getBorderSymbolPositionOf($collision->position());
            $_borderSymbols[$borderSymbolPosition] = $collisionSymbol;

        }

        return $_borderSymbols;
    }
}
