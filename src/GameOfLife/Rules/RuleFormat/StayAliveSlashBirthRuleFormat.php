<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule\RuleFormat;

/**
 * Rule format for the <stayAlive>/<birth> rules notation.
 */
class StayAliveSlashBirthRuleFormat extends BaseRuleFormat
{
    // Magic Methods

    /**
     * StayAliveSlashBirthRuleFormat constructor.
     */
    public function __construct()
    {
        parent::__construct("/^[0-9]*\/[0-9]*\$/");
    }


    // Class Methods

    /**
     * Returns the rule parts as a standard class with the public attributes rulesBirth and rulesStayAlive.
     *
     * @param String $_rules The rules string
     *
     * @return \StdClass The standard class with the public attributes rulesBirth and rulesStayAlive
     *
     * @throws \Exception The exception when the number of rule parts is invalid
     */
    public function getRuleParts(String $_rules): \StdClass
    {
        $ruleParts = explode("/", $_rules);
        $this->validateNumberOfRuleParts($ruleParts, 2, 1);

        $ruleStrings = new \StdClass();
        $ruleStrings->rulesBirth = $ruleParts[1];
        $ruleStrings->rulesStayAlive = $ruleParts[0];

        return $ruleStrings;
    }
}
