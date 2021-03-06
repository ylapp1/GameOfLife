<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

/**
 * Rules for 2/45.
 */
class Two45Rule extends BaseRule
{
    /**
     * Sets the birth/stay alive rules for this rule.
     */
    public function __construct()
    {
        parent::__construct();
        $this->rulesBirth = array(4, 5);
        $this->rulesStayAlive = array(2);
    }
}
