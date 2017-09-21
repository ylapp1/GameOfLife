<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * Class BaseInput
 *
 * Fills the board with cells
 * Used as base for child classes which fill the board with specific sets of cells
 */
class BaseInput
{
    private $objectHeight;
    private $objectWidth;


    // Magic Methods

    /**
     * BaseInput constructor.
     *
     * @param int $_objectWidth     Object width
     * @param int $_objectHeight    Object height
     */
    public function __construct(int $_objectWidth = null, int $_objectHeight = null)
    {
        $this->objectWidth = $_objectWidth;
        $this->objectHeight = $_objectHeight;
    }


    // Getters and Setters

    /**
     * Returns the object height
     *
     * @return int  Object height
     */
    public function objectHeight(): int
    {
        return $this->objectHeight;
    }

    /**
     * Sets the object height
     *
     * @param int $_objectHeight    Object height
     */
    public function setObjectHeight(int $_objectHeight)
    {
        $this->objectHeight = $_objectHeight;
    }

    /**
     * Returns the object width
     *
     * @return int  Object width
     */
    public function objectWidth(): int
    {
        return $this->objectWidth;
    }

    /**
     * Sets the object width
     *
     * @param int $_objectWidth     Object width
     */
    public function setObjectWidth(int $_objectWidth)
    {
        $this->objectWidth = $_objectWidth;
    }


    /**
     * Adds object specific options
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $_options     Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Fills a given board with cells
     *
     * @codeCoverageIgnore
     *
     * @param Board $_board       The board which shall be filled with cells
     * @param Getopt $_options    Object specific options (e.g. posX, posY, fillPercent)
     */
    public function fillBoard(Board $_board, Getopt $_options)
    {
    }

    /**
     * Checks whether the object is out of bounds
     *
     * @param int $_boardWidth  Board width
     * @param int $_boardHeight Board height
     * @param int $_posX        X-Coordinate of the top left border of the object
     * @param int $_posY        Y-Coordinate of the top left border of the object
     *
     * @return bool     True: Object is out of bounds
     *                  False: Object is not out of bounds
     */
    public function isObjectOutOfBounds(int $_boardWidth, int $_boardHeight, int $_posX, int $_posY)
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