<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

/**
 * Standard rules for Comway's Game of Life (Rules for 23/3).
 */
class ConwayRule extends BaseRule
{
    /**
     * Sets the birth/stay alive rules for this rule.
     */
    public function __construct()
    {
        $this->rulesBirth = array(3);
        $this->rulesStayAlive = array(2, 3);
    }
}
