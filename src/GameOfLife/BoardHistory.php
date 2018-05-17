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
class BoardHistory
{
    // Attributes

    /**
     * The list of board fields strings or Board objects
     *
     * @var String[]|Board[] $boards
     */
    private $boards;

    /**
     * Defines how the boards will be saved in the history
     *
     * True: The complete board objects are saved in the history
     * False: The strings of board fields are saved in the history
     *
     * @var Bool $saveEntireObjects
     */
    private $saveEntireObjects;

    /**
     * The number of boards that are stored in the history at once
     *
     * @var int $historySize
     */
    private $historySize;

    /**
     * The current board index
     *
     * @var int $currentBoardIndex
     */
    private $currentBoardIndex;


    // Magic Methods

    /**
     * BoardHistory constructor.
     *
     * @param int $_historySize The number of boards that are stored in the history at once
     * @param Bool $_saveEntireObjects Defines how the boards will be saved in the history
     */
    public function __construct(int $_historySize, Bool $_saveEntireObjects = false)
    {
        $this->boards = array();
        $this->historySize = $_historySize;
        $this->saveEntireObjects = $_saveEntireObjects;
        $this->currentBoardIndex = -1;
    }


    // Class Methods

    /**
     * Adds a board to the history of boards.
     *
     * @param Board $_board The board that will be added to the history of boards
     */
    public function addBoardToHistory(Board $_board)
    {
        if ($this->currentBoardIndex == $this->historySize - 1) array_shift($this->boards);
        else $this->currentBoardIndex++;

        if ($this->saveEntireObjects)
        { // Save the complete Board object in the history
            $this->boards[$this->currentBoardIndex] = clone $_board;
        }
        else
        { // Save only the fields string in the history
            $this->boards[$this->currentBoardIndex] = (String)$_board;
        }
    }

    /**
     * Returns whether a board exists in the history of boards.
     *
     * @param Board $_board The board
     *
     * @return int|null The index of the board in the history or null if the board doesn't exist in the history
     */
    public function boardExistsInHistory(Board $_board)
    {
        if ($this->saveEntireObjects)
        {
            foreach ($this->boards as $index => $board)
            {
                if ($_board->equals($board)) return $index;
            }
        }
        else
        {
            $boardString = (String)$_board;
            foreach ($this->boards as $index => $historyBoardString)
            {
                if ($boardString == $historyBoardString) return $index;
            }
        }

        return null;
    }


    // Get entries from the history

    /**
     * Returns a board from the board history with a specific index.
     *
     * @param int $_index The index of the board in the board history
     *
     * @return String|Board The board from the board history
     */
    public function getBoard(int $_index)
    {
        return $this->boards[$_index];
    }

    /**
     * Returns the board at the current board index.
     *
     * @return String|Board The board at the current board index
     */
    public function getCurrentBoard()
    {
        return $this->boards[$this->currentBoardIndex];
    }

    /**
     * Returns the previous board in the history.
     *
     * @return String|Board|null The previous board or null if no previous board was found
     */
    public function getPreviousBoard()
    {
        if ($this->currentBoardIndex == 0) return null;
        else
        {
            $this->currentBoardIndex--;
            return $this->getCurrentBoard();
        }
    }

    /**
     * Returns the next board in the history.
     *
     * @return String|Board|null The next board or null if no next board was found
     */
    public function getNextBoard()
    {
        if ($this->currentBoardIndex == count($this->boards) - 1) return null;
        else
        {
            $this->currentBoardIndex++;
            return $this->getCurrentBoard();
        }
    }
}
