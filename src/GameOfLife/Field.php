<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores a single field.
 */
class Field
{
    // Attributes

    /**
     * The X-coordinate of the field
     *
     * @var int $x
     */
    private $x;

    /**
     * The Y-coordinate of the field
     *
     * @var int $y
     */
    private $y;

    /**
     * The state of the cell in the field
     *
     * True: The cell is alive
     * False: The cell is dead
     *
     * @var Bool $value
     */
    private $value;

    /**
     * The board to which the field belongs
     *
     * @var Board $parentBoard
     */
    private $parentBoard;


    // Magic Methods

    /**
     * Field constructor.
     *
     * @param int $_x The X-coordinate of the field
     * @param int $_y The Y-coordinate of the field
     * @param Bool $_value The state of the cell in the field
     * @param Board $_parentBoard The board to which the field belongs
     */
    public function __construct(int $_x, int $_y, Bool $_value, Board $_parentBoard = null)
    {
        $this->x = $_x;
        $this->y = $_y;
        $this->value = $_value;
        $this->parentBoard = $_parentBoard;
    }


    // Getters and Setters

    /**
     * Returns the X-coordinate of the field.
     *
     * @return int The X-coordinate of the field
     */
    public function x(): int
    {
        return $this->x;
    }

    /**
     * Sets the X-coordinate of the field.
     *
     * @param int $_x The X-coordinate of the field
     */
    public function setX(int $_x)
    {
        $this->x = $_x;
    }

    /**
     * Returns the Y-coordinate of the field.
     *
     * @return int The Y-coordinate of the field
     */
    public function y(): int
    {
        return $this->y;
    }

    /**
     * Sets the Y-coordinate of the field.
     *
     * @param int $_y The Y-coordinate of the field
     */
    public function setY(int $_y)
    {
        $this->y = $_y;
    }

    /**
     * Returns the state of the cell in the field.
     *
     * @return Bool The state of the cell in the field
     */
    public function value(): Bool
    {
        return $this->value;
    }

    /**
     * Sets the state of the cell in the field.
     *
     * @param Bool $_value The state of the cell in the field
     */
    public function setValue(Bool $_value)
    {
        $this->value = $_value;
    }

    /**
     * Returns the board to which the field belongs.
     *
     * @return Board The board to which the field belongs
     */
    public function parentBoard(): Board
    {
        return $this->parentBoard;
    }

    /**
     * Sets the board to which the field belongs.
     *
     * @param Board $_parentBoard The board to which the field belongs
     */
    public function setParentBoard(Board $_parentBoard)
    {
        $this->parentBoard = $_parentBoard;
    }


    /**
     * Returns whether the cell in the field is alive.
     *
     * @return Bool True: The cell is alive
     *              False: The cell is dead
     */
    public function isAlive(): Bool
    {
        return $this->value;
    }

    /**
     * Returns whether the cell in the field is dead.
     *
     * @return Bool True: The cell is dead
     *              False: The cell is alive
     */
    public function isDead(): Bool
    {
        return ! $this->value;
    }

    /**
     * Inverts the fields value.
     */
    public function invertValue()
    {
        $this->value = ! $this->value;
    }


    // Class Methods

    /**
     * Calculates the number of living neighbor cells.
     *
     * @return int The number of living neighbor cells
     */
    public function numberOfLivingNeighbors(): int
    {
        $neighborFields = $this->parentBoard->getNeighborsOfField($this);
        $numberOfLivingNeighbors = 0;

        foreach ($neighborFields as $neighborField)
        {
            if ($neighborField->isAlive()) $numberOfLivingNeighbors++;
        }

        return $numberOfLivingNeighbors;
    }

    /**
     * Calculates the number of dead neighbor cells.
     *
     * @return int The number of dead neighbor cells
     */
    public function numberOfDeadNeighbors(): int
    {
        $neighborFields = $this->parentBoard->getNeighborsOfField($this);
        $numberOfDeadNeighbors = 0;

        foreach ($neighborFields as $neighborField)
        {
            if ($neighborField->isDead()) $numberOfDeadNeighbors++;
        }

        return $numberOfDeadNeighbors;
    }

    /**
     * Returns the number of neighbor border fields.
     * If the board has no border this function will return 0.
     *
     * @return int The number of neighbor border fields
     */
    public function numberOfNeighborBorderFields(): int
    {
        if ($this->parentBoard->hasBorder())
        {
            /*
             * This calculation assumes that all of the fields are squares of equal size
             * in a grid of rows and columns with the same number of fields per row and column.
             */
            $numberOfBorderingFieldsPerField = 8;

            $neighbors = $this->parentBoard->getNeighborsOfField($this);
            $numberOfNeighbors = count($neighbors);

            return $numberOfBorderingFieldsPerField - $numberOfNeighbors;
        }
        else return 0;
    }
}
