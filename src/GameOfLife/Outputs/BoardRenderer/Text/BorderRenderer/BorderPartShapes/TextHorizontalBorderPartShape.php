<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

class TextHorizontalBorderPartShape
{
    /**
     * HorizontalBaseBorderPart constructor.
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
    public function __construct(Coordinate $_startsAt, Coordinate $_endsAt, String $_borderSymbolStart, String $_borderSymbolCenter, String $_borderSymbolEnd, String $_borderSymbolOuterBorderCollisionStart, String $_borderSymbolOuterBorderCollisionCenter, String $_borderSymbolOuterBorderCollisionEnd, String $_borderSymbolInnerBorderCollisionStart, String $_borderSymbolInnerBorderCollisionCenter, String $_borderSymbolInnerBorderCollisionEnd)
    {
        parent::__construct($_startsAt, $_endsAt, $_borderSymbolStart, $_borderSymbolCenter, $_borderSymbolEnd, $_borderSymbolOuterBorderCollisionStart, $_borderSymbolOuterBorderCollisionCenter, $_borderSymbolOuterBorderCollisionEnd, $_borderSymbolInnerBorderCollisionStart, $_borderSymbolInnerBorderCollisionCenter, $_borderSymbolInnerBorderCollisionEnd);
    }



    /**
     * Adds the border symbols of this border to a border symbol grid.
     *
     * @param SymbolGrid $_borderSymbolGrid The border symbol grid
     */
    public function addBorderSymbolsToBorderSymbolGrid(SymbolGrid $_borderSymbolGrid)
    {
        $startX = $this->startsAt->x() * 2;
        $startY = $this->startsAt->y() * 2;
        $totalBorderLength = $this->getTotalBorderLength();

        if (isset($this->borderCollisionSymbols[0])) $borderSymbolStart = $this->borderCollisionSymbols[0];
        else $borderSymbolStart = $this->borderSymbolStart;

        $_borderSymbolGrid->setSymbolAt(new Coordinate($startX, $startY), $borderSymbolStart);


        for ($x = 0; $x < $totalBorderLength - 1; $x++)
        {
            $xGrid = $startX + 1 + $x * 2;

            if (isset($this->borderCollisionSymbols[$x])) $borderSymbol = $this->borderCollisionSymbols[$x];
            else $borderSymbol = $this->borderSymbolCenter;

            $_borderSymbolGrid->setSymbolAt(new Coordinate($xGrid, $startY), $borderSymbol);
        }

        $borderSymbolEndIndex = $startX + $totalBorderLength * 2;

        if (isset($this->borderCollisionSymbols[$totalBorderLength]))
        {
            $borderSymbolEnd = $this->borderCollisionSymbols[$totalBorderLength];
        }
        else $borderSymbolEnd = $this->borderSymbolEnd;

        $_borderSymbolGrid->setSymbolAt(new Coordinate($borderSymbolEndIndex, $startY), $borderSymbolEnd);
    }
}
