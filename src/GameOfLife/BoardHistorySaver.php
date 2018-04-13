<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores a history of boards.
 */
class BoardHistorySaver
{
    /**
     * The list of fields when $saveOnlyFields is true or the list of Board objects
     *
     * @var array $boards
     */
    private $boards;

    /**
     * Indicates whether only the fields of the boards are saved in the history
     * True: Strings of fields are saved in the history
     * False: The complete board objects are saved in the history
     *
     * @var bool $saveOnlyFields
     */
    private $saveOnlyFields;

    /**
     * The number of boards that are stored in the history.
     *
     * @var int $historySize
     */
    private $historySize;

    /**
     * The current board index.
     *
     * @var int $currentBoardIndex
     */
    private $currentBoardIndex;


    /**
     * BoardHistorySaver constructor.
     *
     * @param int $_historySize The number of boards that are stored in the history
     * @param bool $_saveOnlyFields Indicates whether only the fields of the boards are saved in the history
     */
    public function __construct(int $_historySize, Bool $_saveOnlyFields = true)
    {
        $this->boards = array();
        $this->historySize = $_historySize;
        $this->saveOnlyFields = $_saveOnlyFields;
        $this->currentBoardIndex = -1;
    }


    /**
     * Adds a board to the history of boards.
     *
     * @param Board $_board The board that will be added to the history of boards
     */
    public function addBoardToHistory(Board $_board)
    {
        if ($this->currentBoardIndex == $this->historySize - 1) array_shift($this->boards);
        else $this->currentBoardIndex++;

        if ($this->saveOnlyFields) $this->addFieldsOnlyToHistory($_board);
        else $this->addCompleteBoardToHistory($_board);
    }

    /**
     * Adds a fields string to the history of boards.
     *
     * @param Board $_board The board whose fields will be added to the history
     */
    private function addFieldsOnlyToHistory(Board $_board)
    {
        if (count($this->boards) > $this->historySize) array_shift($this->boards);
        $this->boards[] = (String)$_board;
    }

    /**
     * Adds a complete board object to the history of boards.
     *
     * @param Board $_board The board that will be added to the history
     */
    private function addCompleteBoardToHistory(Board $_board)
    {
        if (count($this->boards) > $this->historySize) array_shift($this->boards);
        $this->boards[$this->currentBoardIndex] = clone $_board;
    }

    /**
     * Returns whether a board exists in the history of boards.
     *
     * @param Board $_board The board
     *
     * @return int|null The index of the board in the history or null
     */
    public function boardExistsInHistory(Board $_board)
    {
        if ($this->saveOnlyFields) return $this->boardStringExistsInHistory($_board);
        else return $this->completeBoardExistsInHistory($_board);
    }

    /**
     * Returns whether a board string exists in the history of boards.
     *
     * @param Board $_board The board
     *
     * @return int|null The index of the board in the history or null
     */
    private function boardStringExistsInHistory(Board $_board)
    {
        $boardString = (String)$_board;

        foreach ($this->boards as $index => $board)
        {
            if ($boardString == $board) return $index;
        }

        return null;
    }

    /**
     * Returns whether a complete board exists in the history of boards.
     *
     * @param Board $_board The board
     *
     * @return int|null The index of the board in the history or null
     */
    private function completeBoardExistsInHistory(Board $_board)
    {
        foreach ($this->boards as $index => $board)
        {
            if ($_board->equals($board)) return $index;
        }

        return null;
    }

    /**
     * Returns the current board
     *
     * @return String|Board The
     */
    public function getCurrentBoard()
    {
        return $this->boards[$this->currentBoardIndex];
    }

    /**
     * Reduces the internal pointer by 1 and calls getCurrentBoard().
     *
     * @return Board|null|String The previous board as a string or Board object or null if no previous board was found
     */
    public function getPreviousBoard()
    {
        if ($this->currentBoardIndex <= 0) return null;
        else
        {
            $this->currentBoardIndex--;
            return $this->getCurrentBoard();
        }
    }

    /**
     * Increments the internal pointer by 1 and calls getCurrentBoard().
     *
     * @return Board|null|String The next board as a string or Board object or null if no previous board was found
     */
    public function getNextBoard()
    {
        if ($this->currentBoardIndex == $this->historySize - 1) return null;
        elseif ($this->currentBoardIndex == count($this->boards) - 1) return null;
        else
        {
            $this->currentBoardIndex++;
            return $this->getCurrentBoard();
        }
    }

    /**
     * Returns the current board index.
     *
     * @return int The current board index
     */
    public function currentBoardIndex(): int
    {
        return $this->currentBoardIndex;
    }
}
