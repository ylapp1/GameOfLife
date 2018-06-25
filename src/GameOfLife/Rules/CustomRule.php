<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Rule;

use Ulrichsg\Getopt;

/**
 * Parses and uses a user inputted rule set.
 *
 * The rules enable three cell state events to happen:
 * 1. Dead cells can be reborn (dead -> alive)
 * 2. Alive cells can die (alive -> dead)
 * 3. Cell states can remain unchanged (dead -> dead, alive -> alive)
 *
 * The possible rule combinations are:
 * - Birth rules set and stay alive rules set: Enables all events
 * - Birth rules set and stay alive rules not set: Enables all events except for cell stay alive
 * - Birth rules not set and stay alive rules set: Enables all events except for cell birth
 * - Birth rules and stay alive rules not set: Enables all events except for cell birth and stay alive
 *
 * All of the above combinations are allowed except for birth rules and stay alive rules not set.
 * The reason is, that every simulation would end after 1 game step because all cells die (stay alive conditions can not
 * be met) and no cells are born (birth conditions can not be met).
 */
class CustomRule extends BaseRule
{
    // Magic Methods

    /**
     * CustomRule constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
        $rulesString = $_options->getOption("rulesString");
        if ($rulesString !== null) $this->parseRulesString($rulesString);
        else
        {
            $rulesBirth = $_options->getOption("rulesBirth");
            $rulesStayAlive = $_options->getOption("rulesStayAlive");
            if ($rulesBirth !== null || $rulesStayAlive !== null)
            {
                $this->parseBirthStayAliveRules($rulesBirth, $rulesStayAlive);
            }
            else throw new \Exception("The rules string is not set.");
        }

        parent::initialize($_options);
    }


    // Check the rules strings

    /**
     * Checks and returns whether a string is a <stayAlive>/<birth> string.
     *
     * @param String $_rules The rules string
     *
     * @return Bool True if the string is a <stayAlive>/<birth> string, false otherwise
     *
     * @throws \Exception The exception when the rules string is in an invalid format
     */
    private function isStayAliveSlashBirthString(String $_rules): Bool
    {
        if (strstr($_rules, "/"))
        {
            $this->validateRulesString($_rules, "/", 2, 1);
            return true;
        }
        else return false;
    }

    /**
     * Checks and returns whether a string is a <stayAlive>G<stayAlive/birth> string.
     *
     * @param String $_rules The rules string
     *
     * @return Bool True if the string is a <stayAlive>G<stayAlive/birth> string, false otherwise
     *
     * @throws \Exception The exception when the rules string is in an invalid format
     */
    private function isAlternateNotationString(String $_rules): Bool
    {
        if (strstr($_rules, "G"))
        {
            $this->validateRulesString($_rules, "G", 2, 1);
            return true;
        }
        else return false;
    }

    /**
     * Checks whether a rules string has the correct number of rule parts and contains only numeric rule parts.
     *
     * @param String $_rules The rules string
     * @param String $_delimiter The delimiter at which the rules string will be split into rule parts
     * @param int $_maxNumberOfRuleParts The maximum allowed number of rule parts
     * @param int $_minNumberOfRuleParts The minimum required number of rule parts
     *
     * @throws \Exception The exception when the rules string is in an invalid format
     */
    private function validateRulesString(String $_rules, String $_delimiter, int $_maxNumberOfRuleParts, int $_minNumberOfRuleParts)
    {
        $ruleParts = explode($_delimiter, $_rules);

        $numberOfSetRuleParts = 0;
        foreach ($ruleParts as $rulePart)
        {
            if ($rulePart != "")
            {
                if (! is_numeric($rulePart)) throw new \Exception("The custom rule parts may contain only numbers.");
                $numberOfSetRuleParts++;
            }
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


    // Parse rules strings

    /**
     * Parses birth and stay alive rules that are strings of digits and sets the birth and stay alive rules of this rule.
     *
     * @param String $_rulesBirth The birth rules string
     * @param String $_rulesStayAlive The stay alive rules string
     *
     * @throws \Exception The exception when one of the strings contains something other than digits
     */
    private function parseBirthStayAliveRules(String $_rulesBirth, String $_rulesStayAlive)
    {
        if (! is_numeric($_rulesBirth)) throw new \Exception("The birth rules string may contain only numbers.");
        elseif (! is_numeric($_rulesStayAlive)) throw new \Exception("The stay alive rules string may contain only numbers.");
        else
        {
            $this->rulesBirth = $this->getRulesFromNumericString($_rulesBirth);
            $this->rulesStayAlive = $this->getRulesFromNumericString($_rulesStayAlive);
        }
    }

    /**
     * Parses a rules string in the format <stayAlive>/<birth> or <stayAlive>G<stayAlive/birth> and sets the birth and
     * stay alive rules of this rule.
     *
     * @param String $_rules The rules string
     *
     * @throws \Exception The exception when the rules string is in an invalid format
     */
    private function parseRulesString(String $_rules)
    {
        if ($this->isStayAliveSlashBirthString($_rules)) $this->parseStayAliveSlashBirthString($_rules);
        elseif ($this->isAlternateNotationString($_rules)) $this->parseAlternateNotationString($_rules);
        else throw new \Exception("Unknown rules notation.");
    }

    /**
     * Parses a <stayAlive>/<birth> string and sets the birth and stay alive rules of this rule.
     *
     * @param String $_rules The rules string
     */
    private function parseStayAliveSlashBirthString(String $_rules)
    {
        $ruleParts = explode("/", $_rules);

        $this->rulesBirth = $this->getRulesFromNumericString($ruleParts[1]);
        $this->rulesStayAlive = $this->getRulesFromNumericString($ruleParts[0]);
    }

    /**
     * Parses a <stayAlive>G<stayAlive/birth> string and sets the birth and stay alive rules of this rule.
     *
     * @param String $_rules The rules string
     */
    private function parseAlternateNotationString(String $_rules)
    {
        $ruleParts = explode("G", $_rules);

        $this->rulesBirth = $this->getRulesFromNumericString($ruleParts[1]);
        $this->rulesStayAlive = $this->getRulesFromNumericString($ruleParts[1]);

        $this->rulesStayAlive = array_merge($this->rulesStayAlive, $this->getRulesFromNumericString($ruleParts[0]));
        sort($this->rulesStayAlive);
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
            sort($numericList);
        }

        return $numericList;
    }
}
