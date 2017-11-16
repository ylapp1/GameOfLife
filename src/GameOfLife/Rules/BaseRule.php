<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
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
     * @var int[] $rulesBirth
     */
    protected $rulesBirth;

    /**
     * Stores the amount of living neighbors which will kill a living cell
     *
     * @var array
     */
    protected $rulesDeath;


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
     * Returns the death rules
     *
     * @return int[] Death rules
     */
    public function rulesDeath(): array
    {
        return $this->rulesDeath;
    }

    /**
     * Sets the death rules
     *
     * @param int[] $_rulesDeath Death rules
     */
    public function setRulesDeath(array $_rulesDeath)
    {
        $this->rulesDeath = $_rulesDeath;
    }


    /**
     * Add rule specific options to the option list.
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $options Option list
     */
    public function addOptions(Getopt $options)
    {
    }

    /**
     * Intialize the rule.
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $options
     */
    public function initialize(Getopt $options)
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
            foreach ($this->rulesDeath as $amountDeath)
            {
                if ($amountLivingNeighbors == $amountDeath) return false;
            }
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