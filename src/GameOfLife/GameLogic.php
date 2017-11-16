<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Handles the game logic.
 *
 * Call calculateNextBoard($_board) to calculate a game step for the entire board
 * Call isLoopDetected() to check whether there's a repeating pattern
 */
class GameLogic
{
    /**
     * Stores the current fields
     *
     * @var Field[][]
     */
    private $currentBoard = array();

    /**
     * Stores the fields of the last 15 boards of the current simulation
     *
     * @var Field[][][] $historyOfBoards
     */
    private $historyOfBoards = array();


    /**
     * Returns the fields of the current board
     *
     * @return Field[][] Fields of the current board
     */
    public function currentBoard(): array
    {
        return $this->currentBoard;
    }

    /**
     * Sets the fields of the current board
     *
     * @param Field[][] $_currentBoard Fields of the current board
     */
    public function setCurrentBoard(array $_currentBoard)
    {
        $this->currentBoard = $_currentBoard;
    }

    /**
     * Returns the history of boards.
     *
     * @return Field[][][] History of boards
     */
    public function historyOfBoards(): array
    {
        return $this->historyOfBoards;
    }

    /**
     * Sets the history of boards.
     *
     * @param Board[] $_historyOfBoards History of boards
     */
    public function setHistoryOfBoards($_historyOfBoards)
    {
        $this->historyOfBoards = $_historyOfBoards;
    }


    /**
     * Adds a board to the history of boards.
     *
     * @param Field[][] $_fields The fields that will be added to the history of boards
     */
    private function addToHistory(array $_fields)
    {
        $this->historyOfBoards[] = $_fields;
        if (count($this->historyOfBoards) > 15) array_shift($this->historyOfBoards);
    }

    /**
     * Calculates one game step for the entire board.
     *
     * @param Board $_board The board
     */
    public function calculateNextBoard(Board $_board)
    {
        $this->addToHistory($_board->fields());
        $_board->calculateStep();
        $this->currentBoard = $_board->fields();
    }

    /**
     * Compares the newest board in the history with up to 15 previous boards.
     *
     * @return bool Indicates whether there's a repeating pattern
     *              True: Repeating pattern detected
     *              False: No repeating pattern detected
     */
    public function isLoopDetected(): bool
    {
        foreach ($this->historyOfBoards as $board)
        {
            if ($this->currentBoard == $board) return true;
        }

        return false;
    }
}