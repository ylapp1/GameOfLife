<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text;


use GameOfLife\Coordinate;

/**
 * The border symbol grid is twice the width and height of the cell symbol grid.
 * The even rows are used for horizontal borders.
 * The uneven rows are used for vertical borders.
 */
class BorderSymbolGrid extends SymbolGrid
{
    /**
     * Sets a border row symbol at a specific position.
     * Border rows are located in the even
     *
     * @param Coordinate $_coordinate
     * @param String $_symbol
     */
    public function setBorderRowSymbolAt(Coordinate $_coordinate, String $_symbol)
    {
        $coordinate = clone $_coordinate;
        $coordinate->setX($coordinate->x() * 2);
        $coordinate->setY($coordinate->y() * 2);

        $this->setSymbolAt($coordinate, $_symbol);
    }

    public function setCellRowSymbolAt(Coordinate $_coordinate, String $_symbol)
    {

    }
}
