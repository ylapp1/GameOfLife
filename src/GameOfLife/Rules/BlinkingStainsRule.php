<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

/**
 * Rules for a world with blinking stains (Rules for 0123/01234).
 */
class BlinkingStainsRule extends BaseRule
{
    /**
     * Sets the birth/stay alive rules for this rule.
     */
    public function __construct()
    {
        parent::__construct();
        $this->rulesBirth = array(0, 1, 2, 3, 4);
        $this->rulesStayAlive = array(0, 1, 2, 3);
    }
}
