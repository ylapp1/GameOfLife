<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule\RuleFormat;

/**
 * Contains information about a rule format for CustomRule strings.
 * Parent class for rule formats.
 */
abstract class BaseRuleFormat
{
    // Attributes

    /**
     * The regular expression that specifies the format of the rules strings for this rule.
     *
     * @var String $rulesStringFormatRegex
     */
    private $rulesStringFormatRegex;


    // Magic Methods

    /**
     * BaseRuleFormat constructor.
     *
     * @param String $_rulesStringFormatRegex
     */
    protected function __construct(String $_rulesStringFormatRegex)
    {
        $this->rulesStringFormatRegex = $_rulesStringFormatRegex;
    }


    // Class Methods

    /**
     * Returns whether a rules string matches this rule format.
     *
     * @param String $_rules The rules string
     *
     * @return Bool True if the rules string matches this rule format, false otherwise
     */
    public function matches(String $_rules): Bool
    {
        if (preg_match($this->rulesStringFormatRegex, $_rules)) return true;
        else return false;
    }

    /**
     * Returns the rule parts as a standard class with the public attributes rulesBirth and rulesStayAlive.
     *
     * @param String $_rules The rules string
     *
     * @return \StdClass The standard class with the public attributes rulesBirth and rulesStayAlive
     */
    abstract public function getRuleParts(String $_rules): \StdClass;

    /**
     * Checks whether a rules string has the correct number of rule parts.
     *
     * @param String[] $_ruleParts The rule parts
     * @param int $_maxNumberOfRuleParts The maximum allowed number of rule parts
     * @param int $_minNumberOfRuleParts The minimum required number of rule parts
     *
     * @throws \Exception The exception when the number of rule parts is invalid
     */
    protected function validateNumberOfRuleParts(array $_ruleParts, int $_maxNumberOfRuleParts, int $_minNumberOfRuleParts)
    {
        $numberOfSetRuleParts = 0;
        foreach ($_ruleParts as $rulePart)
        {
            if ($rulePart != "") $numberOfSetRuleParts++;
        }

        if ($numberOfSetRuleParts < $_minNumberOfRuleParts)
        {
            $exceptionMessageFormat = "The rules string must contain at least %d set rule part%s.";
            if ($_minNumberOfRuleParts == 1) $exceptionMessage = sprintf($exceptionMessageFormat, $_minNumberOfRuleParts, "");
            else $exceptionMessage = sprintf($exceptionMessageFormat, $_minNumberOfRuleParts, "s");

            throw new \Exception($exceptionMessage);
        }
        elseif ($numberOfSetRuleParts > $_maxNumberOfRuleParts)
        {
            $exceptionMessageFormat = "The rules string may contain at most %d set rule part%s.";
            if ($_maxNumberOfRuleParts == 1) $exceptionMessage = sprintf($exceptionMessageFormat, $_maxNumberOfRuleParts, "");
            else $exceptionMessage = sprintf($exceptionMessageFormat, $_maxNumberOfRuleParts, "s");

            throw new \Exception($exceptionMessage);
        }
    }
}
