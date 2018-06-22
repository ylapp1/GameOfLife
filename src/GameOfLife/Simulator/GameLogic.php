<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Simulator;

use GameOfLife\Board;
use GameOfLife\BoardHistory;
use GameOfLife\Field;
use Rule\BaseRule;

/**
 * Handles the game logic.
 *
 * Call calculateNextBoard($_board) to calculate one game step for the entire board
 * Call isLoopDetected() to check whether a repeating board was detected
 */
class GameLogic
{
    // Attributes

    /**
     * Stores the fields of the last 15 boards of the current simulation
     *
     * @var BoardHistory $boardHistory
     */
    private $boardHistory;

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
     * The birth/stay alive rules for this simulation
     *
     * @var BaseRule $rule
     */
    private $rule;


    // Magic Methods

    /**
     * GameLogic constructor.
     *
     * @param BaseRule $_rule The birth/stay alive rules for this simulation
     * @param int $_maxSteps The maximum number of game steps that are calculated before the simulation stops
     */
    public function __construct(BaseRule $_rule, int $_maxSteps)
    {
        $this->boardHistory = new BoardHistory(15);
        $this->gameStep = 1;
        $this->maxSteps = $_maxSteps;
        $this->rule = $_rule;
    }


    // Getters and Setters

    /**
     * Returns the current game step.
     *
     * @return int The current game step
     */
    public function gameStep(): int
    {
        return $this->gameStep;
    }

    /**
     * Sets the current game step.
     *
     * @param int $_gameStep The current game step
     */
    public function setGameStep(int $_gameStep)
    {
        $this->gameStep = $_gameStep;
    }

    /**
     * Returns the maximum number of game steps that are calculated before the simulation stops.
     *
     * @return int The maximum number of game steps that are calculated before the simulation stops
     */
    public function maxSteps(): int
    {
        return $this->maxSteps;
    }

    /**
     * Sets the maximum number of game steps that are calculated before the simulation stops.
     *
     * @param int $_maxSteps The maximum number of game steps that are calculated before the simulation stops
     */
    public function setMaxSteps(int $_maxSteps)
    {
        $this->maxSteps = $_maxSteps;
    }


    // Class methods

    /**
     * Calculates one game step for the entire board and updates the fields of the board.
     *
     * @param Board $_board The board
     */
    public function calculateNextBoard(Board $_board)
    {
        $this->boardHistory->addBoardToHistory($_board);
        $updatedFields = array();

        foreach ($_board->fields() as $y => $rowFields)
        {
            foreach ($rowFields as $x => $rowField)
            {
                /** @var Field $updatedField */
                $updatedField = clone $rowField;
                $newState = $this->rule->calculateNewState($rowField);
                $updatedField->setValue($newState);

                $updatedFields[$y][$x] = $updatedField;
            }
        }

        $_board->setFields($updatedFields);
        $this->gameStep++;
    }

    /**
     * Checks whether the board is empty.
     *
     * @param Board $_board The board
     *
     * @return Bool True if the board is empty, false otherwise
     */
    public function isBoardEmpty(Board $_board): Bool
    {
        if ($_board->getNumberOfAliveFields() == 0) return true;
        else return false;
    }

    /**
     * Checks whether the max step is reached.
     *
     * @return Bool True if the max step is reached, false otherwise
     */
    public function isMaxStepReached(): Bool
    {
        if ($this->gameStep >= $this->maxSteps) return true;
        else return false;
    }

    /**
     * Compares the newest board in the history with up to 15 previous boards for reoccurring board fields.
     *
     * @param Board $_board The board
     *
     * @return Bool True if a repeating pattern was detected, false otherwise
     */
    public function isLoopDetected(Board $_board): Bool
    {
        if ($this->boardHistory->boardExistsInHistory($_board) !== null) return true;
        else return false;
    }
}
