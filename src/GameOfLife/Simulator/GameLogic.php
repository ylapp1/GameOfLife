<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Simulator;

use GameOfLife\Board;
use GameOfLife\BoardHistorySaver;
use GameOfLife\Field;
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
     * Stores the fields of the last 15 boards of the current simulation
     *
     * @var BoardHistorySaver $boardHistorySaver
     */
    private $boardHistorySaver;

    /**
     * Birth/Death rules for this session
     *
     * @var BaseRule $rule
     */
    private $rule;


    /**
     * GameLogic constructor.
     *
     * @param BaseRule $_rule Rule for this session
     */
    public function __construct(BaseRule $_rule)
    {
        $this->boardHistorySaver = new BoardHistorySaver(15);
        $this->rule = $_rule;
    }

    /**
     * Calculates one game step for the entire board.
     *
     * @param Board $_board The board
     */
    public function calculateNextBoard(Board $_board)
    {
        $this->boardHistorySaver->addBoardToHistory($_board);

        /** @var Field[][] $newFields */
        $newFields = $_board->initializeEmptyBoard();

        foreach ($_board->fields() as $line)
        {
            foreach ($line as $field)
            {
                if ($field instanceof Field)
                {
                    $newState = $this->rule->calculateNewState($field);
                    $newFields[$field->y()][$field->x()]->setValue($newState);
                }
            }
        }

        $_board->setFields($newFields);
        $_board->setGameStep($_board->gameStep() + 1);
    }

    /**
     * Checks whether the board is empty.
     *
     * @param Board $_board The board
     *
     * @return bool Indicates whether the board is empty
     */
    public function isBoardEmpty(Board $_board): bool
    {
        if ($_board->getAmountCellsAlive() == 0) return true;
        else return false;
    }

    /**
     * Checks whether the max step is reached.
     *
     * @param Board $_board The board which is checked
     *
     * @return bool true: board is finished
     *              false: board is not finished
     */
    public function isMaxStepsReached(Board $_board): bool
    {
        if ($_board->gameStep() >= $_board->maxSteps() - 1) return true;
        else return false;
    }

    /**
     * Compares the newest board in the history with up to 15 previous boards.
     *
     * @param Board $_board The current board
     *
     * @return bool Indicates whether there's a repeating pattern
     *              True: Repeating pattern detected
     *              False: No repeating pattern detected
     */
    public function isLoopDetected(Board $_board): bool
    {
        if ($this->boardHistorySaver->boardExistsInHistory($_board) !== null) return true;
        else return false;
    }
}
