<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

use Rule\BaseRule;

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
     * @var String
     */
    private $currentBoard = array();

    /**
     * Stores the fields of the last 15 boards of the current simulation
     *
     * @var String[] $historyOfBoards
     */
    private $historyOfBoards = array();

    /**
     * Birth/Death rules for this session
     *
     * @var BaseRule The birth/death rules
     */
    private $rule;


    /**
     * GameLogic constructor.
     *
     * @param BaseRule $_rule Rule for this session
     */
    public function __construct(BaseRule $_rule)
    {
        $this->rule = $_rule;
    }


    /**
     * Returns the fields of the current board
     *
     * @return String Fields of the current board
     */
    public function currentBoard(): String
    {
        return $this->currentBoard;
    }

    /**
     * Sets the fields of the current board
     *
     * @param String $_currentBoard Fields of the current board
     */
    public function setCurrentBoard(String $_currentBoard)
    {
        $this->currentBoard = $_currentBoard;
    }

    /**
     * Returns the history of boards.
     *
     * @return String[] History of boards
     */
    public function historyOfBoards(): array
    {
        return $this->historyOfBoards;
    }

    /**
     * Sets the history of boards.
     *
     * @param String[] $_historyOfBoards History of boards
     */
    public function setHistoryOfBoards($_historyOfBoards)
    {
        $this->historyOfBoards = $_historyOfBoards;
    }

    /**
     * Returns the birth/death rules for this session
     *
     * @return BaseRule The birth/death rules
     */
    public function rule(): BaseRule
    {
        return $this->rule;
    }

    /**
     * Sets the birth/death rules for this session
     *
     * @param BaseRule $_rule The birth/death rules
     */
    public function setRule($_rule)
    {
        $this->rule = $_rule;
    }


    /**
     * Adds a board to the history of boards.
     *
     * @param String $_fields The fields that will be added to the history of boards
     */
    private function addToHistory(String $_fields)
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
        $this->addToHistory($_board);

        $newBoard = $_board->initializeEmptyBoard();

        foreach ($_board->fields() as $line)
        {
            foreach ($line as $field)
            {
                $newState = $this->rule->calculateNewState($field);
                $newBoard[$field->y()][$field->x()]->setValue($newState);
            }
        }

        $_board->setFields($newBoard);
        $_board->setGameStep($_board->gameStep() + 1);
        $this->currentBoard = (string)$_board;
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