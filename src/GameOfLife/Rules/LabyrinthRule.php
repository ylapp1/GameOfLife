<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

/**
 * Rules for a world with labyrinth like end patterns (Rules for 12345/3).
 */
class LabyrinthRule extends BaseRule
{
    /**
     * Sets the birth/stay alive rules for this rule.
     */
    public function __construct()
    {
        parent::__construct();
        $this->rulesBirth = array(3);
        $this->rulesStayAlive = array(1, 2, 3, 4, 5);
    }
}
