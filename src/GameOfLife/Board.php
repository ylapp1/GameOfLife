<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores the configuration and the logic of a game of life simulation
 *
 * call calculateStep() to calculate one game step for the entire board
 * call isFinished() to check whether the board is finished
 */
class Board
{
    private $currentBoard;
    private $gameStep;
    private $hasBorder;
    private $height;
    private $historyOfBoards;
    private $maxSteps;
    private $rules;
    private $width;


    // Magic methods

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
    public function __construct(int $_width, int $_height, int $_maxSteps, bool $_hasBorder, RuleSet $_rules)
    {
        $this->gameStep = 0;
        $this->hasBorder = $_hasBorder;
        $this->height = $_height;
        $this->historyOfBoards = array();
        $this->maxSteps = $_maxSteps;
        $this->rules = $_rules;
        $this->width = $_width;

        // must be called after board height is set
        $this->currentBoard = $this->initializeEmptyBoard();
    }

    /**
     * Converts the board to string
     *
     * @return string   A string representing the board
     */
    public function __toString(): string
    {
        $string = "";

        for ($y = 0; $y < $this->height; $y++)
        {
            for ($x = 0; $x < $this->width; $x++)
            {
                if ($this->getField($x, $y)) $string .= "X";
                else $string .= ".";
            }

            if ($y != $this->height - 1) $string .= "\r\n";
        }

        return $string;
    }


    // Getters and Setters

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
     * @param array $_currentBoard   Current board
     */
    public function setCurrentBoard(array $_currentBoard)
    {
        $this->currentBoard = $_currentBoard;
    }

    /**
     * Returns the current game step
     *
     * @return int  Current game step
     */
    public function gameStep(): int
    {
        return $this->gameStep;
    }

    /**
     * Sets the current game step
     *
     * @param int $_gameStep Current game step
     */
    public function setGameStep(int $_gameStep)
    {
        $this->gameStep = $_gameStep;
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
     * @param bool $_hasBorder   Border type
     *                              true: The border is made of cells that are constantly dead
     *                              false: Each border links to the opposite site of the board
     */
    public function setHasBorder(bool $_hasBorder)
    {
        $this->hasBorder = $_hasBorder;
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
     * @param int $_height   Board height
     */
    public function setHeight(int $_height)
    {
        $this->height = $_height;
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
     * Sets the history of boards
     *
     * @param array $_historyOfBoards    History of boards
     */
    public function setHistoryOfBoards(array $_historyOfBoards)
    {
        $this->historyOfBoards = $_historyOfBoards;
    }

    /**
     * Returns the maximum amount of steps which are calculated before the board stops calculating more steps
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
     * @param int $_maxSteps     Maximum amount of game steps
     */
    public function setMaxSteps(int $_maxSteps)
    {
        $this->maxSteps = $_maxSteps;
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
     * Sets the rule set
     *
     * @param RuleSet $_rules    Death/Birth rules of the current board
     */
    public function setRules(RuleSet $_rules)
    {
        $this->rules = $_rules;
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
     * Sets the board width
     *
     * @param int $_width    Board width
     */
    public function setWidth(int $_width)
    {
        $this->width = $_width;
    }



    /**
     * Adds a board to the history of boards
     * The history of boards stores the last 15 boards of a game
     *
     * @param bool[][] $_board  The board that will be added to the history of boards
     */
    public function addToHistory(array $_board)
    {
        $this->historyOfBoards[] = $_board;
        if (count($this->historyOfBoards) > 15) array_shift($this->historyOfBoards);
    }

    /**
     * Calculates a single step of the board
     *
     *   - Calculates the new cell state for each cell
     *   - Adds last board to history of boards
     *   - Increments game step by 1
     */
    public function calculateStep()
    {
        $newBoard = $this->initializeEmptyBoard();

        for ($y = 0; $y < $this->height; $y++)
        {
            for ($x = 0; $x < $this->width; $x++)
            {
                $amountNeighboursAlive = $this->getAmountNeighboursAlive($x, $y);
                $currentCellState = $this->getField($x, $y);
                $newCellState = $this->getNewCellState($currentCellState, $amountNeighboursAlive);

                if ($newCellState) $newBoard[$y][$x] = true;
            }
        }

        $this->addToHistory($this->currentBoard());
        $this->currentBoard = $newBoard;
        $this->gameStep ++;
    }

    /**
     * Returns the total amount of living cells on the board
     *
     * @return int      Amount of living cells
     */
    public function getAmountCellsAlive(): int
    {
        $amountCellsAlive = 0;
        foreach ($this->currentBoard as $line)
        {
            $amountCellsAlive += array_sum($line);
        }
        return $amountCellsAlive;
    }

    /**
     * Returns the amount of living neighbour cells of a cell
     *
     * @param int $_x   X-Coordinate of the cell that is inspected
     * @param int $_y   Y-Coordinate of the cell that is inspected
     *
     * @return int      Amount of living neighbour cells
     */
    public function getAmountNeighboursAlive(int $_x, int $_y): int
    {
        $columns = array($_x);
        $rows = array($_y);

        // column to the left
        if ($_x == 0)
        {
            if (! $this->hasBorder) $columns[] = $this->width - 1;
        }
        else $columns[] = $_x - 1;

        // column to the right
        if ($_x + 1 == $this->width)
        {
            if (! $this->hasBorder) $columns[] = 0;
        }
        else $columns[] = $_x + 1;

        // row above
        if ($_y == 0)
        {
            if (! $this->hasBorder) $rows[] = $this->height - 1;
        }
        else $rows[] = $_y - 1;

        // row below
        if ($_y + 1 == $this->height)
        {
            if (! $this->hasBorder) $rows[] = 0;
        }
        else $rows[] = $_y + 1;


        // calculate amount of living neighbour cells
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
     * Calculates and returns the center of the board
     *
     * @return int[][]  Coordinates of the center (array structure: [["x"] => X-Coordinate, ["y"] => Y-Coordinate])
     */
    public function getCenter(): array
    {
        $centerX = ceil($this->width / 2) - 1;
        $centerY = ceil($this->height / 2) - 1;

        return array("x" => $centerX, "y" => $centerY);
    }

    /**
     * Returns the status of a specific field
     *
     * @param int $_x   X-Coordinate of the field
     * @param int $_y   Y-Coordinate of the field
     *
     * @return bool     Returns whether the cell is alive (true) or dead (false)
     */
    public function getField (int $_x, int $_y): bool
    {
        return isset($this->currentBoard[$_y][$_x]);
    }

    /**
     * Returns the percentage of cells that are alive
     *
     * @return float    Fill percentage
     */
    public function getFillPercentage(): float
    {
        return (float)($this->getAmountCellsAlive()/($this->width * $this->height));
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
     *
     * @return bool                         New Cell State
     */
    public function getNewCellState(bool $_currentCellState, int $_amountNeighboursAlive): bool
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
     * Returns an empty board
     *
     * Uses the height attribute of this board to determine the amount of nested arrays
     *
     * @return bool[][]      Empty board
     */
    public function initializeEmptyBoard(): array
    {
        $board = array();

        for ($y = 0; $y < $this->height; $y++)
        {
            $board[$y] = array();
        }

        return $board;
    }

    /**
     * Checks whether the board is finished
     *
     * The board is finished when either:
     *   - all cells are dead
     *   - maxSteps is reached
     *   - only tiles are remaining that have a reoccurring pattern within 15 game steps
     *
     * @return bool  true:  board is finished
     *               false: board is not finished
     */
    public function isFinished(): bool
    {
        if ($this->gameStep >= $this->maxSteps) return true;
        elseif ($this->getAmountCellsAlive() == 0) return true;
        else
        {
            // Check history of boards for repeating patterns
            foreach ($this->historyOfBoards as $board)
            {
                if ($this->currentBoard == $board) return true;
            }
        }

        return false;
    }

    /**
     * Resets the current board to an empty board
     */
    public function resetCurrentBoard()
    {
        $this->currentBoard = $this->initializeEmptyBoard();
    }

    /**
     * Sets a field on the board.
     *
     * @param int $_x   X-Coordinate of the cell which shall be set
     * @param int $_y   Y-Coordinate of the cell which shall be set
     * @param boolean $_isAlive     State which the cell will be set to
     *                                  true: alive
     *                                  false: dead
     */
    public function setField(int $_x, int $_y, bool $_isAlive)
    {
        if ($_isAlive) $this->currentBoard[$_y][$_x] = $_isAlive;
        else unset($this->currentBoard[$_y][$_x]);
    }
}