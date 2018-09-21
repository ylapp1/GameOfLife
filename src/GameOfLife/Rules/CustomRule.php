<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

use Rule\RuleFormat\BaseRuleFormat;
use Ulrichsg\Getopt;
use Util\ClassLoader;

/**
 * Parses and uses a user inputted rule set.
 */
class CustomRule extends BaseRule
{
    // Attributes

    /**
     * The class loader
     *
     * @var ClassLoader $classLoader
     */
    private $classLoader;

    /**
     * The list of rule formats.
     *
     * @var BaseRuleFormat[] $ruleFormats
     */
    private $ruleFormats;


    // Magic Methods

    /**
     * RuleFormat constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->classLoader = new ClassLoader();
    }


    // Class Methods

    /**
     * Adds rule specific options to a option list.
     *
     * @param Getopt $_options The option list
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array
            (
                array(null, "rulesString", Getopt::REQUIRED_ARGUMENT, "CustomRule - A rules string in the format <stayAlive>/<birth> or <stayAlive>G<stayAlive/birth>"),
                array(null, "rulesBirth", Getopt::REQUIRED_ARGUMENT, "CustomRule - The numbers of living neighbor cells which will rebirth a dead cell as one string of digits"),
                array(null, "rulesStayAlive", Getopt::REQUIRED_ARGUMENT, "CustomRule - The numbers of living neighbor cells which will keep a living cell alive as one string of digits")
            )
        );
    }

    /**
     * Initializes the rule.
     *
     * @param Getopt $_options The option list
     *
     * @throws \Exception The exception when the rules are in an invalid format or not set
     */
    public function initialize(Getopt $_options)
    {
        $this->ruleFormats = $this->classLoader->loadClasses(__DIR__ . "/RuleFormat", "*RuleFormat.php", array("BaseRuleFormat"), "Rule\\RuleFormat\\");

        $rulesString = $_options->getOption("rulesString");
        if ($rulesString !== null)
        {
            $ruleFormat = $this->getRuleFormat($rulesString);
            if ($ruleFormat === null) throw new \Exception("Unknown rules format.");

            $ruleStrings = $ruleFormat->getRuleParts($rulesString);
            $rulesBirthString = $ruleStrings->rulesBirth;
            $rulesStayAliveString = $ruleStrings->rulesStayAlive;
        }
        else
        {
            $rulesBirthString = $_options->getOption("rulesBirth");
            $rulesStayAliveString = $_options->getOption("rulesStayAlive");
        }

        if ($rulesBirthString !== null || $rulesStayAliveString !== null)
        {
            $this->parseRules($rulesBirthString, $rulesStayAliveString);
        }
        else throw new \Exception("No rules specified.");

        parent::initialize($_options);
    }

    /**
     * Returns the rule format that matches a rules string.
     *
     * @param String $_rules The rules string
     *
     * @return BaseRuleFormat|null The rule format or null if no rule format was found
     */
    private function getRuleFormat(String $_rules)
    {
        foreach ($this->ruleFormats as $ruleFormat)
        {
            if ($ruleFormat->matches($_rules)) return $ruleFormat;
        }

        return null;
    }

    /**
     * Parses birth and stay alive rules and sets the birth and stay alive rules of this rule.
     *
     * @param String $_rulesBirth The birth rules string as a string of digits
     * @param String $_rulesStayAlive The stay alive rules string as a string of digits
     *
     * @throws \Exception The exception when one of the strings contains something other than digits
     */
    private function parseRules(String $_rulesBirth, String $_rulesStayAlive)
    {
        if ($_rulesBirth != "" && ! is_numeric($_rulesBirth)) throw new \Exception("The birth rules string may contain only numbers.");
        elseif ($_rulesStayAlive != "" && ! is_numeric($_rulesStayAlive)) throw new \Exception("The stay alive rules string may contain only numbers.");
        else
        {
            $this->rulesBirth = $this->getRulesFromNumericString($_rulesBirth);
            $this->rulesStayAlive = $this->getRulesFromNumericString($_rulesStayAlive);
        }
    }

    /**
     * Returns an array in the format array(int, int, ...) from a numeric string.
     *
     * @param String $_numericString The numeric string
     *
     * @return int[] The numeric array
     */
    private function getRulesFromNumericString(String $_numericString): array
    {
        $numericList = array();
        if ($_numericString != "")
        {
            $digits = str_split($_numericString);
            foreach ($digits as $digit)
            {
                $numericList[] = (int)$digit;
            }

            $numericList = array_unique($numericList);
            sort($numericList);
        }

        return $numericList;
    }
}
