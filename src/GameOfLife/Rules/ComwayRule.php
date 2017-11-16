<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

/**
 * Standard rules for Comway's Game of Life.
 */
class ComwayRule extends BaseRule
{
    /**
     * Sets the birth and death rules for this rule.
     */
    public function __construct()
    {
        $this->rulesBirth = array(3);
        $this->rulesDeath = array(0, 1, 4, 5, 6, 7, 8);
    }
}