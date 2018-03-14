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
 */
class CustomRule extends BaseRule
{
    /**
     * Initializes the rule.
     *
     * @param Getopt $_options The option list
     */
    public function initialize(Getopt $_options)
    {
        if ($_options->getOption("rulesString") !== null)
        {
            $rulesString = $_options->getOption("rulesString");

            if ($this->isStayAliveSlashBirthString($rulesString))
            {
                $this->parseStayAliveSlashBirthString($rulesString);
            }
            elseif ($this->isAlternateNotationString($rulesString))
            {
                $this->parseAlternateNotationString($rulesString);
            }
            else echo "Error: Unknown rules notation";
        }
        elseif ($_options->getOption("rulesBirth") !== null && $_options->getOption("rulesStayAlive") !== null)
        {
            $rulesBirth = $_options->getOption("rulesBirth");
            $rulesStayAlive = $_options->getOption("rulesStayAlive");

            if (is_numeric($rulesBirth) && is_numeric($rulesStayAlive))
            {
                $this->rulesBirth = $this->getRulesFromNumericString($rulesBirth);
                $this->rulesStayAlive = $this->getRulesFromNumericString($rulesStayAlive);
            }
        }
        else echo "Error: Rules string is not set";

        parent::initialize($_options);
    }

    /**
     * Adds the rule options to a Getopt object.
     *
     * @param Getopt $_options The option list
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array
            (
                array(null, "rulesString", Getopt::REQUIRED_ARGUMENT, "Rule string in the format <stayAlive>/<birth>"),
                array(null, "rulesBirth", Getopt::OPTIONAL_ARGUMENT, "The amounts of cells which will rebirth a dead cell as a single string"),
                array(null, "rulesStayAlive", Getopt::OPTIONAL_ARGUMENT, "The amounts of cells which will keep a living cell alive")
            )
        );
    }


    /**
     * Checks and returns whether a string is a <stayAlive>/<birth> string.
     *
     * @param String $_rulesString The rules string
     *
     * @return Bool True: The string is a <stayAlive>/<birth> string
     *              False: The string is not a <stayAlive>/<birth> string
     */
    private function isStayAliveSlashBirthString(String $_rulesString): Bool
    {
        if (strstr($_rulesString, "/"))
        {
            $ruleParts = explode("/", $_rulesString);

            foreach ($ruleParts as $index => $rulePart)
            {
                if ($rulePart == "") unset($ruleParts[$index]);
            }

            if (count($ruleParts) < 2) echo "Error: The custom rule must have at least 1 birth condition and 1 stay alive condition\n";
            elseif (count($ruleParts) == 2)
            {
                if (is_numeric($ruleParts[0]) && is_numeric($ruleParts[1]))
                {
                    return true;
                }
                else echo "Error: The custom rule parts may only contain \"/\" and numbers\n";
            }
            else echo "Error: The custom rule parts may not contain more than two number strings\n";
        }

        return false;
    }

    /**
     * Parses a <stayAlive>/<birth> string and sets the birth and stay alive rules.
     *
     * @param String $_rulesString
     */
    private function parseStayAliveSlashBirthString(String $_rulesString)
    {
        $ruleParts = explode("/", $_rulesString);

        $this->rulesBirth = $this->getRulesFromNumericString($ruleParts[1]);
        $this->rulesStayAlive = $this->getRulesFromNumericString($ruleParts[0]);
    }

    /**
     * Checks and returns whether a string is a <stayAlive>G<stayAlive/birth> string.
     *
     * @param String $_rulesString The rules string
     *
     * @return Bool True: The string is a <stayAlive>G<stayAlive/birth> string
     *              False: The string is not a <stayAlive>G<stayAlive/birth> string
     */
    private function isAlternateNotationString(String $_rulesString): Bool
    {
        if (strstr($_rulesString, "G"))
        {
            $ruleParts = explode("G", $_rulesString);

            if (count($ruleParts) < 2) echo "Error: The custom rule must have at least 1 birth condition and 1 stay alive condition\n";
            elseif (count($ruleParts) > 2) echo "Error: The custom rule parts may not contain more than two number strings\n";
            else
            {
                if ((is_numeric($ruleParts[0]) || $ruleParts[0] == "") && is_numeric($ruleParts[1])) return true;
                else echo "Error: The custom rule parts may only contain \"G\" and numbers\n";
            }

        }

        return false;
    }

    /**
     * Parses a <stayAlive>G<stayAlive/birth> string and sets the birth and stay alive rules.
     *
     * @param String $_rulesString
     */
    private function parseAlternateNotationString(String $_rulesString)
    {
        $ruleParts = explode("G", $_rulesString);

        $this->rulesBirth = $this->getRulesFromNumericString($ruleParts[count($ruleParts) - 1]);
        $this->rulesStayAlive = $this->getRulesFromNumericString($ruleParts[count($ruleParts) - 1]);

        if (count($ruleParts) > 1)
        {
            $this->rulesStayAlive = array_merge($this->rulesStayAlive, $this->getRulesFromNumericString($ruleParts[0]));
            sort($this->rulesStayAlive);
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
        $rules = array_map(
            function(String $_number)
            {
                return (int)$_number;
            },
            str_split($_numericString)
        );

        sort($rules);

        return $rules;
    }
}
