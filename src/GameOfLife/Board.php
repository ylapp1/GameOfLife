<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores the fields of a game of life simulation.
 */
class Board
{
    /**
     * Stores the fields of the current game step
     *
     * @var Field[][] $fields
     */
    private $fields;

    /**
     * Stores the current game step
     * Used to check whether maxSteps is reached
     *
     * @var int $gameStep
     */
    private $gameStep;

    /**
     * Defines whether the board has a border
     *
     * False: Borders are dead cells
     * True: Borders link to the opposite side of the field
     *
     * @var bool $hasBorder
     */
    private $hasBorder;

    /**
     * Defines the board height
     *
     * @var int $height
     */
    private $height;

    /**
     * Defines the maximum amount of steps that are calculated before the simulation stops
     *
     * @var int $maxSteps
     */
    private $maxSteps;

    /**
     * Defines the board width
     *
     * @var int $width
     */
    private $width;


    /**
     * Board constructor.
     *
     * @param int $_width Board width
     * @param int $_height Board height
     * @param int $_maxSteps Maximum amount of game steps that will be calculated before the simulation stops
     * @param bool $_hasBorder Defines the field border type
     *                         false: borders are dead cells
     *                         true: borders link to the opposite side of the field
     */
    public function __construct(int $_width, int $_height, int $_maxSteps, bool $_hasBorder)
    {
        $this->gameStep = 0;
        $this->hasBorder = $_hasBorder;
        $this->height = $_height;
        $this->maxSteps = $_maxSteps;
        $this->width = $_width;

        // must be called after board height is set
        $this->fields = $this->initializeEmptyBoard();
    }

    /**
     * Converts the board to string.
     *
     * @return String A string representing the board
     */
    public function __toString(): String
    {
        $string = "";

        for ($y = 0; $y < $this->height; $y++)
        {
            for ($x = 0; $x < $this->width; $x++)
            {
                if ($this->getFieldStatus($x, $y)) $string .= "X";
                else $string .= ".";
            }

            if ($y != $this->height - 1) $string .= "\r\n";
        }

        return $string;
    }


    /**
     * Returns current Board.
     *
     * @return Field[][] Current board
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * Sets current board.
     *
     * @param Field[][] $_fields Current board
     */
    public function setFields(array $_fields)
    {
        $this->fields = $_fields;
    }

    /**
     * Returns the current game step.
     *
     * @return int Current game step
     */
    public function gameStep(): int
    {
        return $this->gameStep;
    }

    /**
     * Sets the current game step.
     *
     * @param int $_gameStep Current game step
     */
    public function setGameStep(int $_gameStep)
    {
        $this->gameStep = $_gameStep;
    }

    /**
     * Returns the border type.
     *
     * @return bool Border type
     *              true: The border is made of cells that are constantly dead
     *              false: Each border links to the opposite site of the board
     */
    public function hasBorder(): bool
    {
        return $this->hasBorder;
    }

    /**
     * Sets the border type.
     *
     * @param bool $_hasBorder Border type
     *                         true: The border is made of cells that are constantly dead
     *                         false: Each border links to the opposite site of the board
     */
    public function setHasBorder(bool $_hasBorder)
    {
        $this->hasBorder = $_hasBorder;
    }

    /**
     * Returns the board height.
     *
     * @return int Board height
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Sets the board height.
     *
     * @param int $_height Board height
     */
    public function setHeight(int $_height)
    {
        $this->height = $_height;
    }

    /**
     * Returns the maximum amount of steps which are calculated before the board stops calculating more steps.
     *
     * @return int Maximum amount of game steps
     */
    public function maxSteps(): int
    {
        return $this->maxSteps;
    }

    /**
     * Sets the maximum amount of steps which are calculated before the board stops calculating more steps.
     *
     * @param int $_maxSteps Maximum amount of game steps
     */
    public function setMaxSteps(int $_maxSteps)
    {
        $this->maxSteps = $_maxSteps;
    }

    /**
     * Returns the board width.
     *
     * @return int Board width
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Sets the board width.
     *
     * @param int $_width Board width
     */
    public function setWidth(int $_width)
    {
        $this->width = $_width;
    }


    /**
     * Returns the total amount of living cells on the board.
     *
     * @return int Amount of living cells
     */
    public function getAmountCellsAlive(): int
    {
        $amountCellsAlive = 0;
        foreach ($this->fields as $line)
        {
            foreach ($line as $field)
            {
                if ($field->isAlive()) $amountCellsAlive++;
            }
        }
        return $amountCellsAlive;
    }

    /**
     * Calculates and returns the center of the board.
     *
     * @return int[][] Coordinates of the center (array structure: [["x"] => X-Coordinate, ["y"] => Y-Coordinate])
     */
    public function getCenter(): array
    {
        $centerX = ceil($this->width / 2) - 1;
        $centerY = ceil($this->height / 2) - 1;

        return array("x" => $centerX, "y" => $centerY);
    }

    /**
     * Returns the status of a specific field.
     *
     * @param int $_x X-Coordinate of the field
     * @param int $_y Y-Coordinate of the field
     *
     * @return bool Returns whether the cell is alive (true) or dead (false)
     */
    public function getFieldStatus (int $_x, int $_y): bool
    {
        return $this->fields[$_y][$_x]->isAlive();
    }

    /**
     * Returns the percentage of cells that are alive.
     *
     * @return float Fill percentage
     */
    public function getFillPercentage(): float
    {
        return (float)($this->getAmountCellsAlive()/($this->width * $this->height));
    }

    /**
     * Returns an empty board.
     *
     * Uses the height attribute of this board to determine the amount of nested arrays
     *
     * @return Field[][] Empty board
     */
    public function initializeEmptyBoard(): array
    {
        $board = array();

        for ($y = 0; $y < $this->height; $y++)
        {
            $board[$y] = array();
            for ($x = 0; $x < $this->width; $x++)
            {
                $board[$y][] = new Field($x, $y, false, $this);
            }
        }

        return $board;
    }

    /**
     * Resets the current board to an empty board.
     */
    public function resetBoard()
    {
        $this->fields = $this->initializeEmptyBoard();
    }

    /**
     * Inverts the board.
     */
    public function invertBoard()
    {
        foreach ($this->fields as $row)
        {
            foreach ($row as $field)
            {
                $field->setValue(! $field->value());
            }
        }
    }

    /**
     * Sets a field on the board.
     *
     * @param int $_x   X-Coordinate of the cell which shall be set
     * @param int $_y   Y-Coordinate of the cell which shall be set
     * @param boolean $_isAlive State which the cell will be set to
     *                          true: alive
     *                          false: dead
     */
    public function setField(int $_x, int $_y, bool $_isAlive)
    {
        $this->fields[$_y][$_x]->setValue($_isAlive);
    }

    /**
     * Returns the neighbor fields of $_field.
     *
     * @param Field $_field The field whose the neighbors will be returned
     * @return Field[] Neighbor fields of $_field
     */
    public function getNeighborsOfField(Field $_field): array
    {
        $x = $_field->x();
        $y = $_field->y();

        $columns = array($x);
        $rows = array($y);

        // column to the left
        if ($x == 0)
        {
            if (!$this->hasBorder) $columns[] = $this->width - 1;
        }
        else $columns[] = $x - 1;

        // column to the right
        if ($x + 1 == $this->width)
        {
            if (!$this->hasBorder) $columns[] = 0;
        }
        else $columns[] = $x + 1;

        // row above
        if ($y == 0)
        {
            if (!$this->hasBorder) $rows[] = $this->height - 1;
        }
        else $rows[] = $y - 1;

        // row below
        if ($y + 1 == $this->height)
        {
            if (!$this->hasBorder) $rows[] = 0;
        }
        else $rows[] = $y + 1;


        $neighbors = array();

        foreach ($rows as $y)
        {
            foreach ($columns as $x)
            {
                if ($y != $_field->y() || $x != $_field->x()) $neighbors[] = $this->fields[$y][$x];
            }
        }

        return $neighbors;
    }
}
