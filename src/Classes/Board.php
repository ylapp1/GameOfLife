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
    private $gameStep;


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
        $this->gameStep = 0;
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
     * Returns the current game step
     *
     * @return int
     */
    public function gameStep(): int
    {
        return $this->gameStep;
    }

    /**
     * Set the current game step
     *
     * @param int $gameStep
     */
    public function setGameStep(int $gameStep)
    {
        $this->gameStep = $gameStep;
    }


    /**
     * Adds a board to the history of boards
     * The history of boards stores the last 15 boards of a game
     *
     * @param bool[][] $_board
     */
    public function addToHistoryOfBoards($_board)
    {
        $this->historyOfBoards[] = $_board;

        if (count($this->historyOfBoards) > 15) array_shift($this->historyOfBoards);
    }


    /**
     * Returns an empty board
     *
     * @return int[][]      Empty board
     */
    public function initializeEmptyBoard ()
    {
        $board = array();

        for ($y = 0; $y < $this->height; $y++)
        {
            $board[$y] = array();
        }

        return $board;
    }


    /**
     * Returns the amount of living neighbour cells of a cell
     *
     * @param int $_x   X-Coordinate of the cell that is inspected
     * @param int $_y   Y-Coordinate of the cell that is inspected
     * @return int      Amount of living neighbour cells
     */
    public function calculateAmountNeighboursAlive($_x, $_y)
    {
        // find row above
        if ($_y - 1 < 0)
        {
            if ($this->hasBorder) $rowAbove = null;
            else $rowAbove = $this->height - 1;
        }
        else $rowAbove = $_y - 1;

        // find row below
        if ($_y + 1 >= $this->height)
        {
            if ($this->hasBorder) $rowBelow = null;
            else $rowBelow = 0;
        }
        else $rowBelow = $_y + 1;

        // find column to the left
        if ($_x - 1 < 0)
        {
            if ($this->hasBorder) $columnLeft = null;
            else $columnLeft = $this->width - 1;
        }
        else $columnLeft = $_x - 1;

        // find column to the right
        if ($_x + 1 >= $this->width)
        {
            if ($this->hasBorder) $columnRight = null;
            else $columnRight = 0;
        }
        else $columnRight = $_x + 1;


        // save all rows and all columns to check in an array
        $rows = array($rowBelow, $_y, $rowAbove);
        $columns = array($columnLeft, $_x, $columnRight);

        // calculate amount of living nearby cells
        $amountLivingNeighbours = 0;

        foreach ($rows as $y)
        {
            foreach ($columns as $x)
            {
                if ($this->getField($x, $y)) $amountLivingNeighbours++;
            }
        }

        if ($this->getField($_x, $_y)) $amountLivingNeighbours -= 1;

        return $amountLivingNeighbours;
    }

    /**
     * Calculate the new cell state based on the current cell state and the amount of living neighbours
     *
     * Cell states:
     *
     * true = alive
     * false = dead
     *
     * @param bool $_currentCellState       Current Cell State
     * @param int $_amountNeighboursAlive   Amount of living neighbour cells
     * @return bool                         New Cell State
     */
    public function calculateNewCellState($_currentCellState, $_amountNeighboursAlive)
    {
        $newCellState = $_currentCellState;

        // if current cell is alive
        if ($_currentCellState)
        {
            foreach ($this->rules->death() as $amountDeath)
            {
                if ($_amountNeighboursAlive == $amountDeath)
                {
                    $newCellState = false;
                    break;
                }
            }
        }
        // if current cell is dead
        else
        {
            foreach ($this->rules->birth() as $amountBirth)
            {
                if ($_amountNeighboursAlive == $amountBirth)
                {
                    $newCellState = true;
                    break;
                }
            }
        }

        return $newCellState;
    }

    /**
     * Calculates a single step of the board
     */
    public function calculateStep()
    {
        $newBoard = $this->initializeEmptyBoard();

        for ($y = 0; $y < $this->height; $y++)
        {
            for ($x = 0; $x < $this->width; $x++)
            {
                $amountNeighboursAlive = $this->calculateAmountNeighboursAlive($x, $y);
                $currentCellState = $this->getField($x, $y);
                $newCellState = $this->calculateNewCellState($currentCellState, $amountNeighboursAlive);

                if ($newCellState) $newBoard[$y][$x] = true;
            }
        }

        $this->addToHistoryOfBoards($this->currentBoard());
        $this->currentBoard = $newBoard;
        $this->gameStep ++;
    }

    /**
     * Checks whether the board is finished (only static or blinking tiles remaining).
     * Returns true if board is finished and false if board is not finished yet
     *
     * @return bool
     */
    public function isFinished()
    {
        if ($this->gameStep >= $this->maxSteps)
        {
            return true;
        }
        else
        {
            // Check history of boards for repeating patterns
            foreach ($this->historyOfBoards as $board)
            {
                if ($this->currentBoard == $board) return true;
            }

            return false;
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
        if ($_isAlive) $this->currentBoard[$_y][$_x] = $_isAlive;
        else unset($this->currentBoard[$_y][$_x]);
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
        return isset($this->currentBoard[$_y][$_x]);
    }

    /**
     * Returns the total amount of living cells on the board
     *
     * @return int      Amount of living cells
     */
    public function getAmountCellsAlive()
    {
        $amountCellsAlive = 0;
        foreach ($this->currentBoard as $line)
        {
            $amountCellsAlive += array_sum($line);
        }
        return $amountCellsAlive;
    }

    /**
     * Convert board to string
     *
     * @return string
     */
    public function __toString()
    {
        $string = "";

        for ($y = 0; $y < $this->height(); $y++)
        {
            for ($x = 0; $x < $this->width(); $x++)
            {
                if ($this->getField($x, $y)) $string .= "o";
                else $string .= ".";
            }

            if ($y != $this->height() - 1) $string .= "\r\n";
        }

        return $string;
    }
}