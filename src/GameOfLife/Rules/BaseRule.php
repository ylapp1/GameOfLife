<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

use GameofLife\Field;
use Ulrichsg\Getopt;

/**
 * Parent class from which other rules must inherit.
 * Handles the birth/death logic of the game of life
 */
abstract class BaseRule
{
    // Attributes

    /**
     * The numbers of living neighbor cells which will rebirth a dead cell
     *
     * @var int[] $rulesBirth
     */
    protected $rulesBirth;

    /**
     * The numbers of living neighbor cells which will keep a living cell alive
     *
     * @var int[] $rulesStayAlive
     */
    protected $rulesStayAlive;

    /**
     * Indicates whether the rule is an anti rule
     * Anti rule means that cells that would be dead normally are alive and the cells that are alive normally are dead
     *
     * @var Bool $isAntiRule
     */
    protected $isAntiRule;


    // Magic Methods

    /**
     * BaseRule constructor.
     */
    protected function __construct()
    {
        $this->isAntiRule = false;
        $this->rulesBirth = array();
        $this->rulesStayAlive = array();
    }


    // Getters and Setters

    /**
     * Returns the numbers of living neighbor cells which will rebirth a dead cell.
     *
     * @return int[] The numbers of living neighbor cells which will rebirth a dead cell
     */
    public function rulesBirth(): array
    {
        return $this->rulesBirth;
    }

    /**
     * Sets the numbers of living neighbor cells which will rebirth a dead cell.
     *
     * @param int[] $_rulesBirth The numbers of living neighbor cells which will rebirth a dead cell
     */
    public function setRulesBirth(array $_rulesBirth)
    {
        $this->rulesBirth = $_rulesBirth;
    }

    /**
     * Returns the numbers of living neighbor cells which will keep a living cell alive.
     *
     * @return int[] The numbers of living neighbor cells which will keep a living cell alive
     */
    public function rulesStayAlive(): array
    {
        return $this->rulesStayAlive;
    }

    /**
     * Sets the numbers of living neighbor cells which will keep a living cell alive.
     *
     * @param int[] $_rulesStayAlive The numbers of living neighbor cells which will keep a living cell alive
     */
    public function setRulesStayAlive(array $_rulesStayAlive)
    {
        $this->rulesStayAlive = $_rulesStayAlive;
    }


    // Class Methods

    /**
     * Adds rule specific options to a option list.
     *
     * @param Getopt $_options The option list
     */
    abstract public function addOptions(Getopt $_options);

    /**
     * Initializes the rule.
     *
     * @param Getopt $_options The option list
     *
     * @throws \Exception The exception when the rule contains neither birth rules nor stay alive rules
     */
    public function initialize(Getopt $_options)
    {
        if (! $this->rulesBirth && ! $this->rulesStayAlive)
        {
            throw new \Exception("The rule must contain at least either birth or stay alive rules.");
        }

        if ($_options->getOption("antiRules") !== null)
        {
            $this->isAntiRule = true;
            $this->convertToAntiRule();
        }
    }

    /**
     * Converts this rule to its anti rule.
     * This is done by rotating the rules by 180° which is reached by switching the birth and stay alive rules
     * and mirroring them.
     */
    private function convertToAntiRule()
    {
        $rulesStayAlive = range(0, 8);
        foreach ($this->rulesBirth as $numberOfNeighborsBirth)
        {
            unset($rulesStayAlive[8 - $numberOfNeighborsBirth]);
        }

        $rulesBirth = range(0, 8);
        foreach ($this->rulesStayAlive as $numberOfNeighborsStayAlive)
        {
            unset($rulesBirth[8 - $numberOfNeighborsStayAlive]);
        }

        $this->rulesStayAlive = array_values($rulesStayAlive);
        $this->rulesBirth = array_values($rulesBirth);
    }

    /**
     * Calculates and returns the new state of a cell in a field.
     *
     * @param Field $_field The field
     *
     * @return Bool The new state of the cell in the field
     */
    public function calculateNewState(Field $_field): Bool
    {
        $numberOfLivingNeighbors = $_field->numberOfLivingNeighbors();
        if ($this->isAntiRule)
        {
            // When the rule is an anti rule the normally dead border fields must be treated as living fields too
            $numberOfLivingNeighbors += $_field->numberOfNeighborBorderFields();
        }

        if ($_field->isAlive())
        { // The cell is alive
            if (in_array($numberOfLivingNeighbors, $this->rulesStayAlive)) $newCellState = true;
            else $newCellState = false;
        }
        else
        { // The cell is dead
            if (in_array($numberOfLivingNeighbors, $this->rulesBirth)) $newCellState = true;
            else $newCellState = $_field->value();
        }

        return $newCellState;
    }
}
