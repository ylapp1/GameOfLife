<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

/**
 * Rules for a copy world.
 */
class CopyRule extends BaseRule
{
    /**
     * Sets the birth/death rules for this rule.
     */
    public function __construct()
    {
        $this->rulesBirth = array(1, 3, 5, 7);
        $this->rulesDeath = array(0, 2, 4, 6, 8);
    }
}