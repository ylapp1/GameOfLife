<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class BaseInput
 *
 * Fills the board with cells
 * Used as base for child classes which fill the board with specific sets of cells
 */
class BaseInput
{
    private $objectWidth;
    private $objectHeight;

    /**
     * BaseInput constructor.
     *
     * @param int $_objectWidth     Object width
     * @param int $_objectHeight    Object height
     */
    public function __construct($_objectWidth = null, $_objectHeight = null)
    {
        $this->objectWidth = $_objectWidth;
        $this->objectHeight = $_objectHeight;
    }

    /**
     * @return mixed
     */
    public function objectWidth()
    {
        return $this->objectWidth;
    }

    /**
     * @param mixed $objectWidth
     */
    public function setObjectWidth($objectWidth)
    {
        $this->objectWidth = $objectWidth;
    }

    /**
     * @return mixed
     */
    public function objectHeight()
    {
        return $this->objectHeight;
    }

    /**
     * @param mixed $objectHeight
     */
    public function setObjectHeight($objectHeight)
    {
        $this->objectHeight = $objectHeight;
    }


    /**
     * Adds object specific options
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $_options     Option list to which the objects options are added
     */
    public function addOptions($_options)
    {
    }

    /**
     * Fills a given board with cells
     *
     * @codeCoverageIgnore
     *
     * @param Board $_board      The board which shall be filled with cells
     * @param Getopt $_options    Object specific options (e.g. posX, posY, fillPercent)
     */
    public function fillBoard($_board, $_options)
    {
    }

    /**
     * Checks whether the object is out of bounds
     *
     * @param int $_boardWidth  Board width
     * @param int $_boardHeight Board height
     * @param int $_posX        X-Coordinate of the top left border of the object
     * @param int $_posY        Y-Coordinate of the top left border of the object
     * @return bool
     */
    public function isObjectOutOfBounds($_boardWidth, $_boardHeight, $_posX, $_posY)
    {
        if ($_posX < 0 ||
            $_posY < 0 ||
            $_posX + $this->objectWidth > $_boardWidth ||
            $_posY + $this->objectHeight > $_boardHeight)
        {
            return true;
        }
        else return false;
    }
}