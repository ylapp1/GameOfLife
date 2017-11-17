<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

/**
 * Rules for a copy world (Rules for 1357/1357).
 */
class CopyRule extends BaseRule
{
    /**
     * Sets the birth/stay alive rules for this rule.
     */
    public function __construct()
    {
        $this->rulesBirth = array(1, 3, 5, 7);
        $this->rulesStayAlive = array(1, 3, 5, 7);
    }
}