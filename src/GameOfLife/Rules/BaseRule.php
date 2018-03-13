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
 *
 * Handles the birth/death logic of the game of life
 */
class BaseRule
{
    /**
     * Stores the amount of living neighbor cells which will rebirth a dead cell
     *
     * @var int[] $rulesBirth Amount of living neighbor cells which rebirths a dead cell
     */
    protected $rulesBirth;

    /**
     * Stores the amount of living neighbors which will keep a living cell alive
     *
     * @var int[] Amount of living neighbors which keeps a living cell alive
     */
    protected $rulesStayAlive;


    /**
     * BaseRule constructor.
     */
    public function __construct()
    {
        $this->rulesBirth = array();
        $this->rulesStayAlive = array();
    }


    /**
     * Returns the birth rules
     *
     * @return int[] Birth rules
     */
    public function rulesBirth(): array
    {
        return $this->rulesBirth;
    }

    /**
     * Sets the birth rules
     *
     * @param int[] $_rulesBirth Birth rules
     */
    public function setRulesBirth(array $_rulesBirth)
    {
        $this->rulesBirth = $_rulesBirth;
    }

    /**
     * Returns the stay alive rules
     *
     * @return int[] Stay alive rules
     */
    public function rulesStayAlive(): array
    {
        return $this->rulesStayAlive;
    }

    /**
     * Sets the stay alive rules
     *
     * @param int[] $_rulesStayAlive Stay alive rules
     */
    public function setRulesStayAlive(array $_rulesStayAlive)
    {
        $this->rulesStayAlive = $_rulesStayAlive;
    }


    /**
     * Add rule specific options to the option list.
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $_options The option list
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Intialize the rule.
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $_options The option list
     */
    public function initialize(Getopt $_options)
    {
    }

    /**
     * Calculate the new cell state of $_field.
     *
     * @param Field $_field Field for which the new cell state will be calculated
     *
     * @return bool New cell state
     */
    public function calculateNewState(Field $_field): bool
    {
        $amountLivingNeighbors = $_field->numberOfLivingNeighbors();

        if ($_field->isAlive())
        {
            foreach ($this->rulesStayAlive as $amountStayAlive)
            {
                if ($amountLivingNeighbors == $amountStayAlive) return true;
            }

            return false;
        }
        else
        {
            foreach ($this->rulesBirth as $amountBirth)
            {
                if ($amountLivingNeighbors == $amountBirth) return true;
            }
        }

        // Return same field value if the cell neither dies nor rebirths
        return $_field->value();
    }
}
