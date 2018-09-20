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
	 * The X/Y position of this field on the board
	 *
	 * @var Coordinate $coordinate
	 */
	private $coordinate;

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
    	// TODO: Change constructor arg x and y to coordinate $_coordinate
        $this->coordinate = new Coordinate($_x, $_y);
        $this->value = $_value;
        $this->parentBoard = $_parentBoard;
    }


    // Getters and Setters

	/**
	 * Returns the X/Y position of this field on the board.
	 *
	 * @return Coordinate The X/Y position of this field on the board
	 */
	public function coordinate(): Coordinate
	{
		return $this->coordinate;
	}

	/**
	 * Sets the X/Y position of this field on the board.
	 *
	 * @param Coordinate $_coordinate The X/Y position of this field on the board
	 */
	public function setCoordinate(Coordinate $_coordinate)
	{
		$this->coordinate = $_coordinate;
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
     * @return Bool True if the cell is alive, false otherwise
     */
    public function isAlive(): Bool
    {
        return $this->value;
    }

    /**
     * Returns whether the cell in the field is dead.
     *
     * @return Bool True if the cell is dead, false otherwise
     */
    public function isDead(): Bool
    {
        return ! $this->value;
    }

    /**
     * Inverts the state of the cell in the field.
     * If the cell is alive its state will be changed to dead, if the cell is dead its state will be changed to alive.
     */
    public function invertValue()
    {
        $this->value = ! $this->value;
    }


    // Class Methods

    /**
     * Calculates and returns the number of living neighbor cells.
     *
     * @return int The number of living neighbor cells
     */
    public function numberOfLivingNeighbors(): int
    {
        $neighborFields = $this->parentBoard->getNeighborsOfField($this);

        $numberOfLivingNeighbors = 0;
        foreach ($neighborFields as $neighborField)
        {
            $numberOfLivingNeighbors += (int)$neighborField->isAlive();
        }

        return $numberOfLivingNeighbors;
    }

    /**
     * Calculates and returns the number of dead neighbor cells.
     *
     * @return int The number of dead neighbor cells
     */
    public function numberOfDeadNeighbors(): int
    {
        $neighborFields = $this->parentBoard->getNeighborsOfField($this);

        $numberOfDeadNeighbors = 0;
        foreach ($neighborFields as $neighborField)
        {
            $numberOfDeadNeighbors += (int)$neighborField->isDead();
        }

        return $numberOfDeadNeighbors;
    }

    /**
     * Calculates and returns the number of neighbor fields that are borders instead of cells.
     * This function will return 0 if the board has a "passthrough" border
     *
     * @return int The number of neighbor fields that are borders instead of cells
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
