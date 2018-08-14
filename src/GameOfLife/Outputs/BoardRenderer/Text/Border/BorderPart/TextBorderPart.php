<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\BorderPart;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BaseBorder;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use Output\BoardRenderer\Text\Border\BorderPart\Shapes\TextHorizontalBorderPartShape;
use Output\BoardRenderer\Text\Border\BorderPart\Shapes\TextVerticalBorderPartShape;
use Output\BoardRenderer\Text\Border\SymbolDefinition\BorderSymbolDefinition;

/**
 * Container that stores the information about a part of a border.
 * This class uses text symbols to render the border part.
 */
class TextBorderPart extends BaseBorderPart
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

    /**
     * The symbol for the start of the border when the start collides with a border
     *
     * @var String $collisionSymbolStart
     */
    protected $collisionSymbolStart;

    /**
     * The symbol for the center parts of the border when a center part collides with an outer border
     *
     * @var String $borderSymbolOuterBorderCollisionCenter
     */
    protected $collisionSymbolCenter;

    /**
     * The symbol for the end of the border when the end collides with an outer border
     *
     * @var String $borderSymbolOuterBorderCollisionEnd
     */
    protected $collisionSymbolEnd;


    // Magic Methods

    /**
     * TextBorderPart constructor.
     *
     * @param BaseBorder $_parentBorder The parent border of this border part
     * @param Coordinate $_startsAt The start coordinate of this border
     * @param Coordinate $_endsAt The end coordinate of this border
     * @param TextHorizontalBorderPartShape|TextVerticalBorderPartShape $_shape The shape of this border part
     * @param String $_borderSymbolStart The symbol for the start of the border
     * @param String $_borderSymbolCenter The symbol for the center parts of the border
     * @param String $_borderSymbolEnd The symbol for the end of the border
     * @param String $_borderSymbolOuterBorderCollisionStart The symbol for the start of the border when the start collides with an outer border
     * @param String $_borderSymbolOuterBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an outer border
     * @param String $_borderSymbolOuterBorderCollisionEnd The symbol for the end of the border when the end collides with an outer border
     * @param String $_borderSymbolInnerBorderCollisionStart The symbol for the start of the border when the start collides with an inner border
     * @param String $_borderSymbolInnerBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an inner border
     * @param String $_borderSymbolInnerBorderCollisionEnd The symbol for the end of the border when the end collides with an inner border
     */
    public function __construct($_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, $_shape, String $_borderSymbolStart, String $_borderSymbolCenter, String $_borderSymbolEnd, String $_borderSymbolOuterBorderCollisionStart = null, String $_borderSymbolOuterBorderCollisionCenter = null, String $_borderSymbolOuterBorderCollisionEnd = null, String $_borderSymbolInnerBorderCollisionStart = null, String $_borderSymbolInnerBorderCollisionCenter = null, String $_borderSymbolInnerBorderCollisionEnd = null)
    {
        parent::__construct($_parentBorder, $_startsAt, $_endsAt, $_shape);

        $this->borderSymbolDefinition = new BorderSymbolDefinition($_borderSymbolStart, $_borderSymbolCenter, $_borderSymbolEnd);

        // TODO: Default collision symbols = normal border symbols
	    // TODO: Fix collision start left, collision start top, collision start right and all else

	    $this->collisionSymbolStart = $_borderSymbolStart;
	    $this->collisionSymbolCenter = $_borderSymbolCenter;
	    $this->collisionSymbolEnd = $_borderSymbolEnd;
    }


    // Getters and Setters

	public function borderSymbolDefinition(): BorderSymbolDefinition
	{
		return $this->borderSymbolDefinition;
	}

    public function collisionSymbolStart()
    {
        return $this->collisionSymbolStart;
    }

    public function collisionSymbolCenter()
    {
        return $this->collisionSymbolCenter;
    }

    public function collisionSymbolEnd()
    {
        return $this->collisionSymbolEnd;
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
	    foreach ($this->collisions as $collision)
        {
            if ($collision->isOuterBorderPartCollision()) $dominatingBorderPart = $collision->with();
            else $dominatingBorderPart = $this;

            if ($collision->position()->equals($dominatingBorderPart->startsAt()))
            {
            	$collisionSymbol = $dominatingBorderPart->collisionSymbolStart();
            }
            elseif ($collision->position()->equals($dominatingBorderPart->endsAt()))
            {
            	$collisionSymbol = $dominatingBorderPart->collisionSymbolEnd();
            }
            else $collisionSymbol = $dominatingBorderPart->collisionSymbolCenter();

            $borderSymbolPosition = $this->shape->getBorderSymbolPositionOf($collision->position());
            $_borderSymbols[$borderSymbolPosition] = $collisionSymbol;

        }

        return $_borderSymbols;
    }
}
