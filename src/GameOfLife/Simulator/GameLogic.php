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
     * The current game step
     * This is used to check whether maxSteps is reached
     *
     * @var int $gameStep
     */
    private $gameStep;

    /**
     * The maximum number of game steps that are calculated before the simulation stops
     *
     * @var int $maxSteps
     */
    private $maxSteps;

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
     * @param int $_maxSteps The maximum number of game steps that are calculated before the simulation stops
     */
    public function __construct(BaseRule $_rule, int $_maxSteps)
    {
        $this->boardHistorySaver = new BoardHistorySaver(15);
        $this->gameStep = 1;
        $this->maxSteps = $_maxSteps;
        $this->rule = $_rule;
    }


    /**
     * Returns the current game step.
     *
     * @return int Current game step
     */
    public function gameStep(): int
    {
        return $this->gameStep;
    }

    /**
     * Sets the current game step.
     *
     * @param int $_gameStep Current game step
     */
    public function setGameStep(int $_gameStep)
    {
        $this->gameStep = $_gameStep;
    }

    /**
     * Returns the maximum amount of steps which are calculated before the board stops calculating more steps.
     *
     * @return int The maximum amount of game steps
     */
    public function maxSteps(): int
    {
        return $this->maxSteps;
    }

    /**
     * Sets the maximum amount of steps which are calculated before the board stops calculating more steps.
     *
     * @param int $_maxSteps The maximum amount of game steps
     */
    public function setMaxSteps(int $_maxSteps)
    {
        $this->maxSteps = $_maxSteps;
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
        $newFields = $_board->generateFieldsList(false);

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
        $this->gameStep++;
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
        if ($_board->getNumberOfAliveFields() == 0) return true;
        else return false;
    }

    /**
     * Checks whether the max step is reached.
     *
     * @return bool true: board is finished
     *              false: board is not finished
     */
    public function isMaxStepsReached(): bool
    {
        if ($this->gameStep >= $this->maxSteps) return true;
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
