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
 */
class Board
{
    private $width;
    private $height;
    private $board = array(array());
    private $previousBoard = array(array());
    private $hasBorder;                 /** @var Boolean $hasBorder */
    private $rules = array(array());    // ruleset (array structure:
                                        // "Birth" => amount of neighbours which will cause a dead cell to be reborn
                                        // "Death" => amount of neighbours which will cause a living cell to die


    public function __construct($_width, $_height, $_hasBorder, $_rules)
    {
        $this->width = $_width;
        $this->height = $_height;
        $this->hasBorder = $_hasBorder;
        $this->rules = $_rules;

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
     * Creates a board with random cells
     */
    public function createRandomBoard ()
    {
        $board = $this->initializeEmptyBoard();

        for ($i = 0; $i < $this->height; $i++)
        {
            for ($j = 0; $j < $this->width; $j++)
            {
                $board[$i][$j] = rand(0, 1);
            }
        }


        $this->board = $board;
    }


    /**
     * Creates a board with one glider
     */
    public function createGliderBoard ()
    {
        $this->board = $this->initializeEmptyBoard();

        $this->setField(0, 1, true);
        $this->setField(1, 2, true);
        $this->setField(2, 0, true);
        $this->setField(2, 1, true);
        $this->setField(2, 2, true);
    }


    /**
     * Creates a board with one spaceship
     */
    public function createSpaceShipBoard ()
    {
        $board = $this->initializeEmptyBoard();

        $board[4][1] = true;
        $board[4][2] = true;
        $board[4][3] = true;
        $board[4][4] = true;
        $board[5][0] = true;
        $board[5][4] = true;
        $board[6][4] = true;
        $board[7][0] = true;
        $board[7][3] = true;

        $this->board = $board;
    }



    public function calculateCells()
    {
        $newBoard = new Board($this->width, $this->height, $this->hasBorder, $this->rules);

        // Go through each row (y)
        for ($y = 0; $y < $this->height; $y++)
        {
            // Go through each column of the row (x)
            for ($x = 0; $x < $this->width; $x++)
            {
                $currentCellState = $this->board[$y][$x];
                $amountNeighboursAlive = $this->checkAmountNeighboursAlive($x, $y);


                // check whether cell dies, is reborn or stays alive
                $newCellState = $currentCellState;

                // if current cell is dead
                if ($currentCellState == false)
                {
                    foreach ($this->rules["Birth"] as $amountBirth)
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
                    foreach ($this->rules["Death"] as $amountDeath)
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

        $this->previousBoard = $this->board;
        $this->board = $newBoard->board;
    }


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


    public function checkBoardFinish()
    {
        /*
        $sum = 0;

        foreach ($this->board as $line)
        {
            $sum = $sum + array_sum($line);
        }


        if ($sum == 0) return false;
        else return true;
        */

        if ($this->previousBoard == $this->board) return false;
        else return true;
    }


    public function printBoard()
    {
        echo "\n\n ";

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



    public function setField ($_x, $_y, $_value)
    {
        $this->board[$_y][$_x] = $_value;
    }
}