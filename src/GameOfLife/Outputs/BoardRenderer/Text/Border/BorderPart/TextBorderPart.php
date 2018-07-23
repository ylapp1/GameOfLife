<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\BorderPart;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\BaseSymbolGrid;
use Output\BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use Output\BoardRenderer\Text\SymbolGrid;

class TextBorderPart extends BaseBorderPart
{
    // Attributes

    /**
     * The symbol for the start of the border
     *
     * @var String $borderSymbolStart
     */
    protected $borderSymbolStart;

    /**
     * The symbol for the center parts of the border
     *
     * @var String $borderSymbolCenter
     */
    protected $borderSymbolCenter;

    /**
     * The symbol for the end of the border
     *
     * @var String $borderSymbolEnd
     */
    protected $borderSymbolEnd;

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



    /**
     * The border collision symbols inside this border
     *
     * @var String[] $borderCollisionSymbols
     */
    //protected $borderCollisionSymbols;


    /**
     * TextBorderPart constructor.
     *
     * @param Coordinate $_startsAt The start coordinate of this border
     * @param Coordinate $_endsAt The end coordinate of this border
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
    protected function __construct(Coordinate $_startsAt, Coordinate $_endsAt, String $_borderSymbolStart, String $_borderSymbolCenter, String $_borderSymbolEnd, String $_borderSymbolOuterBorderCollisionStart, String $_borderSymbolOuterBorderCollisionCenter, String $_borderSymbolOuterBorderCollisionEnd, String $_borderSymbolInnerBorderCollisionStart, String $_borderSymbolInnerBorderCollisionCenter, String $_borderSymbolInnerBorderCollisionEnd)
    {
        $this->borderSymbolStart = $_borderSymbolStart;
        $this->borderSymbolCenter = $_borderSymbolCenter;
        $this->borderSymbolEnd = $_borderSymbolEnd;
        $this->borderSymbolOuterBorderCollisionStart = $_borderSymbolOuterBorderCollisionStart;
        $this->borderSymbolOuterBorderCollisionCenter = $_borderSymbolOuterBorderCollisionCenter;
        $this->borderSymbolOuterBorderCollisionEnd = $_borderSymbolOuterBorderCollisionEnd;
        $this->borderSymbolInnerBorderCollisionStart = $_borderSymbolInnerBorderCollisionStart;
        $this->borderSymbolInnerBorderCollisionCenter = $_borderSymbolInnerBorderCollisionCenter;
        $this->borderSymbolInnerBorderCollisionEnd = $_borderSymbolInnerBorderCollisionEnd;

        $this->borderCollisionSymbols = array();
    }


    public function borderSymbolStart()
    {
        return $this->borderSymbolStart;
    }

    public function borderSymbolCenter()
    {
        return $this->borderSymbolCenter;
    }

    public function borderSymbolEnd()
    {
        return $this->borderSymbolEnd;
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

    protected function renderBorderPart()
    {
        $borderSymbols = array();

        $borderSymbols[0] = $this->borderSymbolStart;
        for ($i = 0; $i < $this->getTotalLength() - 2; $i++)
        {
            $borderSymbols[$i] = $this->borderSymbolCenter;
        }
        $borderSymbols[$this->getTotalLength() - 1] = $this->borderSymbolEnd;

        return $borderSymbols;
    }

    protected function renderCollisions(array $_borderSymbols)
    {
        foreach ($this->collisions as $collision)
        {
            $collidingBorder = $collision->with();
            if ($collidingBorder instanceof TextBorderPart)
            {
                if ($collision->position() == 0)
                {
                    $collisionSymbol = $collidingBorder->collisionSymbolStart();
                }
                elseif ($collision->position() == $this->getTotalLength())
                {
                    $collisionSymbol = $collidingBorder->collisionSymbolEnd();
                }
                else $collisionSymbol = $collidingBorder->collisionSymbolCenter();

                $_borderSymbols[$collision->position()] = $collisionSymbol;
            }
        }

        return $_borderSymbols;
    }

    /**
     * Renders this border part and adds it to a symbol grid.
     *
     * @param BaseSymbolGrid $_symbolGrid The symbol grid
     */
    public function addToSymbolGrid($_symbolGrid)
    {
        $this->shape->drawBorderPartToSymbolGrid($_symbolGrid);
    }
}
