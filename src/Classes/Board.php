<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */


namespace CN_Consult\GameOfLife\Classes;


/**
 * Class Board
 *
 * call initializeRandomBoard(), initializeGliderBoard() or initializeSpaceShipBoard() to create a new board
 * call printBoard() to print the current board to the console
 * call calculateStep() to calculate one game step for the entire board
 * call isFinished() to check whether the board is finished
 */
class Board
{
    private $board = array(array());
    private $previousBoard = array(array());
    private $secondLastBoard = array(array());
    private $hasBorder;
    private $height;
    private $maxSteps;
    private $width;
    private $rules;


    /**
     * Board constructor.
     *
     * @param int $_width       Width of the field
     * @param int $_height      Height of the field
     * @param int $_maxSteps    Maximum amount of game steps that will be calculated before the board stops
     * @param bool $_hasBorder  defines the field border type
     *                              false: borders are dead cells
     *                              true: borders link to the opposite side of the field
     * @param RuleSet $_rules   contains Birth/Death rules of the board
     */
    public function __construct($_width, $_height, $_maxSteps, $_hasBorder, $_rules)
    {
        $this->hasBorder = $_hasBorder;
        $this->height = $_height;
        $this->maxSteps = $_maxSteps;
        $this->rules = $_rules;
        $this->width = $_width;

        $this->board = $this->initializeEmptyBoard();
    }



    /**
     * Returns an empty board
     */
    private function initializeEmptyBoard ()
    {
        $board = array();


        for ($i = 0; $i < $this->height; $i++)
        {
            $board[$i] = array();
            $board[$i] = array_fill(0, $this->width, false);
        }

        return $board;
    }



    /**
     * Initializes a board with random set cells
     */
    public function initializeRandomBoard ()
    {
        $board = $this->initializeEmptyBoard();

        for ($y = 0; $y < $this->height; $y++)
        {
            for ($x = 0; $x < $this->width; $x++)
            {
                $board[$y][$x] = rand(0, 1);
            }
        }


        $this->board = $board;
    }


    /**
     * Initializes a board with one glider in the top left corner
     */
    public function initializeGliderBoard ()
    {
        $this->board = $this->initializeEmptyBoard();

        $this->setField(1, 0, true);
        $this->setField(2, 1, true);
        $this->setField(0, 2, true);
        $this->setField(1, 2, true);
        $this->setField(2, 2, true);
    }


    /**
     * Initializes a board with one spaceship in the top left corner
     */
    public function initializeSpaceShipBoard ()
    {
        $this->board = $this->initializeEmptyBoard();

        $this->setField(1, 4, true);
        $this->setField(2, 4, true);
        $this->setField(3, 4, true);
        $this->setField(4, 4, true);
        $this->setField(0, 5, true);
        $this->setField(4, 5, true);
        $this->setField(4, 6, true);
        $this->setField(0, 7, true);
        $this->setField(3, 7, true);
    }


    /**
     * Initializes a board with a blinking 3x1 tile in the top left corner
     */
    public function initializeBlinkBoard ()
    {
        $this->board = $this->initializeEmptyBoard();

        $this->setField(1,0, true);
        $this->setField(1,1,true);
        $this->setField(1,2,true);
    }


    /**
     * Calculates a single step of the board
     */
    public function calculateStep()
    {
        $newBoard = new Board($this->width, $this->height, $this->maxSteps, $this->hasBorder, $this->rules);

        // Go through each row
        for ($y = 0; $y < $this->height; $y++)
        {
            // Go through each column of the row
            for ($x = 0; $x < $this->width; $x++)
            {
                $currentCellState = $this->board[$y][$x];
                $amountNeighboursAlive = $this->checkAmountNeighboursAlive($x, $y);


                // check whether cell dies, is reborn or stays alive
                $newCellState = $currentCellState;

                // if current cell is dead
                if ($currentCellState == false)
                {
                    foreach ($this->rules->birth() as $amountBirth)
                    {
                        if ($amountNeighboursAlive == $amountBirth)
                        {
                            $newCellState = true;
                        }
                    }
                }

                // if current cell is alive
                else
                {
                    foreach ($this->rules->death() as $amountDeath)
                    {
                        if ($amountNeighboursAlive == $amountDeath)
                        {
                            $newCellState = false;
                        }
                    }
                }


                $newBoard->setField($x, $y, $newCellState);
            }
        }

        $this->secondLastBoard = $this->previousBoard;
        $this->previousBoard = $this->board;
        $this->board = $newBoard->board;
    }


    /**
     * Returns the amount of living neighbour cells of a cell
     *
     * @param int $_x   X-Coordinate of the cell that is inspected
     * @param int $_y   Y-Coordinate of the cell that is inspected
     * @return int      Amount of living neighbour cells
     */
    public function checkAmountNeighboursAlive($_x, $_y)
    {
        // find row above
        if ($_y - 1 < 0)
        {
            if ($this->hasBorder == true) $rowAbove = $_y;
            else $rowAbove = $this->height - 1;
        }
        else $rowAbove = $_y - 1;


        // find row below
        if ($_y + 1 >= $this->height)
        {
            if ($this->hasBorder == true) $rowBelow = $_y;
            else $rowBelow = 0;
        }
        else $rowBelow = $_y + 1;


        // find column to the left
        if ($_x - 1 < 0)
        {
            if ($this->hasBorder == true) $columnLeft = $_x;
            else $columnLeft = $this->width - 1;
        }
        else $columnLeft = $_x - 1;


        // find column to the right
        if ($_x + 1 >= $this->width)
        {
            if ($this->hasBorder == true) $columnRight = $_x;
            else $columnRight = 0;
        }
        else $columnRight = $_x + 1;



        // save all rows and all columns to check in an array
        $rows = array($rowBelow, $_y, $rowAbove);
        $columns = array($columnLeft, $_x, $columnRight);

        // remove duplicated entries (if there is a border)
        $rows = array_unique($rows, SORT_NUMERIC);
        $columns = array_unique($columns, SORT_NUMERIC);


        // get amount of living nearby cells
        $amountLivingNeighbours = 0;


        foreach ($rows as $row)
        {
            foreach ($columns as $column)
            {
                if ($this->board[$row][$column] == 1) $amountLivingNeighbours++;
            }
        }

        if ($this->board[$_y][$_x] == true)
        {
            $amountLivingNeighbours -= 1;
        }


        return $amountLivingNeighbours;
    }


    /**
     * Checks whether the board is finished (only static or blinking tiles remaining).
     * Returns true if board is finished and false if board is not finished yet
     *
     * @param int $_curStep     current game step
     *
     * @return bool
     */
    public function isFinished($_curStep)
    {
        if ($_curStep >= $this->maxSteps)
        {
            return true;
        }
        else
        {
            if ($this->previousBoard == $this->board) return true;
            elseif ($this->secondLastBoard == $this->board) return true;
            else return false;
        }
    }


    /**
     * Print the current board to the console
     */
    public function printBoard()
    {
        echo "\n ";

        for ($i = 0; $i < $this->width; $i++)
        {
            echo "-";
        }

        foreach ($this->board as $line)
        {
            echo "\n|";

            foreach ($line as $cell)
            {
                if ($cell === true) echo "*";
                else echo " ";
            }

            echo "|";
        }


        echo "\n ";

        for ($i = 0; $i < $this->width; $i++)
        {
            echo "-";
        }
    }


    /**
     * Sets a field on the board.
     *
     * @param int $_x   X-Coordinate of the cell which shall be set
     * @param int $_y   Y-Coordinate of the cell which shall be set
     * @param boolean $_isAlive     State which the cell shall be set to
     *                                  true: alive
     *                                  false: dead
     */
    public function setField ($_x, $_y, $_isAlive)
    {
        $this->board[$_y][$_x] = $_isAlive;
    }
}