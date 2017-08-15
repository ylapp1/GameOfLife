<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

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
    private $currentBoard = array(array());
    private $historyOfBoards = array();
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

        $this->currentBoard = $this->initializeEmptyBoard();
    }


    /**
     * Returns current Board
     *
     * @return array   Current board
     */
    public function currentBoard(): array
    {
        return $this->currentBoard;
    }

    /**
     * Sets current board
     *
     * @param array $currentBoard   Current board
     */
    public function setCurrentBoard(array $currentBoard)
    {
        $this->currentBoard = $currentBoard;
    }

    /**
     * Returns the history of boards
     *
     * @return array   History of boards
     */
    public function historyOfBoards(): array
    {
        return $this->historyOfBoards;
    }

    /**
     * Set the history of boards
     *
     * @param array $historyOfBoards    History of boards
     */
    public function setHistoryOfBoards(array $historyOfBoards)
    {
        $this->historyOfBoards = $historyOfBoards;
    }

    /**
     * Returns the border type
     *
     * @return bool     Border type
     *                      true: The border is made of cells that are constantly dead
     *                      false: Each border links to the opposite site of the board
     */
    public function hasBorder(): bool
    {
        return $this->hasBorder;
    }

    /**
     * Sets the border type
     *
     * @param bool $hasBorder   Border type
     *                              true: The border is made of cells that are constantly dead
     *                              false: Each border links to the opposite site of the board
     */
    public function setHasBorder(bool $hasBorder)
    {
        $this->hasBorder = $hasBorder;
    }

    /**
     * Returns the board height
     *
     * @return int  Board height
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Sets the board height
     *
     * @param int $height   Board height
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    /**
     * Return the maximum amount of steps which are calculated before the board stops calculating more steps
     *
     * @return int   Maximum amount of game steps
     */
    public function maxSteps(): int
    {
        return $this->maxSteps;
    }

    /**
     * Sets the maximum amount of steps which are calculated before the board stops calculating more steps
     *
     * @param int $maxSteps     Maximum amount of game steps
     */
    public function setMaxSteps(int $maxSteps)
    {
        $this->maxSteps = $maxSteps;
    }

    /**
     * Returns the board width
     *
     * @return int  Board width
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Set the board width
     *
     * @param int $width    Board width
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
    }

    /**
     * Returns the rule set
     *
     * @return RuleSet  Death/Birth rules of the current board
     */
    public function rules(): RuleSet
    {
        return $this->rules;
    }

    /**
     * Set the rule set
     *
     * @param RuleSet $rules    Death/Birth rules of the current board
     */
    public function setRules(RuleSet $rules)
    {
        $this->rules = $rules;
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
     * Calculates a single step of the board
     */
    public function calculateStep()
    {
        $newBoard = $this->initializeEmptyBoard();

        // Go through each row
        for ($y = 0; $y < $this->height; $y++)
        {
            // Go through each column of the row
            for ($x = 0; $x < $this->width; $x++)
            {
                $currentCellState = $this->currentBoard[$y][$x];
                $amountNeighboursAlive = $this->checkAmountNeighboursAlive($x, $y);

                // check whether cell dies, is reborn or stays alive
                $newCellState = $currentCellState;

                // if current cell is dead
                if ($currentCellState == false)
                {
                    foreach ($this->rules->birth() as $amountBirth)
                    {
                        if ($amountNeighboursAlive == $amountBirth) $newCellState = true;
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

                $newBoard[$y][$x] = $newCellState;
            }
        }

        $this->historyOfBoards[] = $this->currentBoard;
        $this->currentBoard = $newBoard;
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
                if ($this->currentBoard[$row][$column] == 1) $amountLivingNeighbours++;
            }
        }

        if ($this->currentBoard[$_y][$_x] == true)
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
            foreach ($this->historyOfBoards as $board)
            {
                if ($this->currentBoard == $board) return true;
            }

            return false;
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

        foreach ($this->currentBoard as $line)
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
        echo "\n";
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
        $this->currentBoard[$_y][$_x] = $_isAlive;
    }


    /**
     * Returns the status of a specific field
     *
     * @param int $_x   X-Coordinate of the field
     * @param int $_y   Y-Coordinate of the field
     *
     * @return boolean
     */
    public function getField ($_x, $_y)
    {
        return $this->currentBoard[$_y][$_x];
    }
}