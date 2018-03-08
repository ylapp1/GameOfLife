<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores the data of a single cell.
 */
class Field
{
    /**
     * Status of the current cell
     *
     * True: Cell is alive
     * False: Cell is dead
     *
     * @var bool $value
     */
    private $value;

    /**
     * Board to which this cell belongs
     *
     * @var Board $parentBoard
     */
    private $parentBoard;

    /**
     * X-Position on the parent board
     *
     * @var int $x
     */
    private $x;

    /**
     * Y-Position on the parent board
     *
     * @var int $y
     */
    private $y;


    /**
     * Field constructor.
     *
     * @param Board $_parentBoard Board to which this cell belongs
     * @param int $_x X-Position on the parent board
     * @param int $_y Y-Position on the parent board
     */
    public function __construct($_parentBoard, $_x, $_y)
    {
        $this->parentBoard = $_parentBoard;
        $this->x = $_x;
        $this->y = $_y;
        $this->value = false;
    }


    /**
     * Returns the value of this cell.
     *
     * @return bool Value of this cell
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Sets the value of this cell.
     *
     * @param bool $_value Value of this cell
     */
    public function setValue($_value)
    {
        $this->value = $_value;
    }

    /**
     * Sets the board to which this cell belongs.
     *
     * @return Board Board to which this cell belongs
     */
    public function parentBoard()
    {
        return $this->parentBoard;
    }

    /**
     * Returns the board to which this cell belongs.
     *
     * @param Board $_parentBoard Board to which this cell belongs
     */
    public function setParentBoard($_parentBoard)
    {
        $this->parentBoard = $_parentBoard;
    }

    /**
     * Returns the X-Position of this cell on the parent board.
     *
     * @return int X-Position of this cell on the parent board
     */
    public function x()
    {
        return $this->x;
    }

    /**
     * Sets the X-Position of this cell on the parent board.
     *
     * @param int $_x X-Position of this cell on the parent board
     */
    public function setX($_x)
    {
        $this->x = $_x;
    }

    /**
     * Returns the Y-Position of this cell on the parent board.
     *
     * @return int Y-Position of this cell on the parent board
     */
    public function y()
    {
        return $this->y;
    }

    /**
     * Sets the Y-Position of this cell on the parent board.
     *
     * @param int $_y Y-Position of this cell on the parent board
     */
    public function setY($_y)
    {
        $this->y = $_y;
    }


    /**
     * Returns whether this cell is alive.
     *
     * @return bool Indicates whether this cell is alive
     *              True: Cell is alive
     *              False: Cell is dead
     */
    public function isAlive()
    {
        return $this->value;
    }

    /**
     * Returns whether this cell is dead.
     *
     * @return bool Indicates whether this cell is dead
     *              True: Cell is dead
     *              False: Cell is alive
     */
    public function isDead()
    {
        return ! $this->value;
    }

    /**
     * Calculates the amount of living neighbor cells.
     *
     * @return int Amount of living neighbor cells
     */
    public function numberOfLivingNeighbors()
    {
        /** @var Field[] $neighbors */
        $neighbors = $this->parentBoard->getNeighborsOfField($this);
        $neighborsAlive = 0;

        foreach ($neighbors as $neighbor)
        {
            if ($neighbor->isAlive()) $neighborsAlive++;
        }

        return $neighborsAlive;
    }

    /**
     * Calculates the amount of dead neighbor cells.
     *
     * @return int Amount of dead neighbor cells
     */
    public function numberOfDeadNeighbors()
    {
        /** @var Field[] $neighbors */
        $neighbors = $this->parentBoard->getNeighborsOfField($this);
        $neighborsDead = 0;

        foreach ($neighbors as $neighbor)
        {
            if ($neighbor->isDead()) $neighborsDead++;
        }

        return $neighborsDead;
    }
}
