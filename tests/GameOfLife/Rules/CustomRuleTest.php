<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Rule\CustomRule;
use Ulrichsg\Getopt;

/**
 * Checks whether the custom rule works as expected.
 */
class CustomRuleTest extends TestCase
{
    /**
     * Checks whether the rule can add its options to the option list.
     *
     * @covers \Rule\CustomRule::addOptions()
     */
    public function testCanAddOptions()
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $expectedOptionList = array
        (
            array(null, "rulesString", Getopt::REQUIRED_ARGUMENT, "Rule string in the format <stayAlive>/<birth>"),
            array(null, "rulesBirth", Getopt::REQUIRED_ARGUMENT, "The amounts of cells which will rebirth a dead cell as a single string"),
            array(null, "rulesStayAlive", Getopt::REQUIRED_ARGUMENT, "The amounts of cells which will keep a living cell alive")
        );

        $optionsMock->expects($this->exactly(1))
                    ->method("addOptions")
                    ->with($expectedOptionList);

        $rule = new CustomRule();
        if ($optionsMock instanceof \Ulrichsg\Getopt) $rule->addOptions($optionsMock);
    }

    /**
     * Checks whether the rule can be initialized.
     *
     * @param String|null $_rulesString The rules string
     * @param int[] $_expectedRulesBirth The expected birth rules array
     * @param int[] $_expectedRulesStayAlive The expected stay alive rules array
     * @param String $_expectedErrorMessage The expected error message
     *
     * @dataProvider parseRuleStringsProvider()
     *
     * @covers \Rule\CustomRule::initialize()
     * @covers \Rule\CustomRule::isStayAliveSlashBirthString()
     * @covers \Rule\CustomRule::parseStayAliveSlashBirthString()
     * @covers \Rule\CustomRule::isAlternateNotationString()
     * @covers \Rule\CustomRule::parseAlternateNotationString()
     * @covers \Rule\CustomRule::getRulesFromNumericString()
     */
    public function testCanParseRuleStrings($_rulesString, array $_expectedRulesBirth, array $_expectedRulesStayAlive, String $_expectedErrorMessage = "")
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        if ($_rulesString == null)
        {
            $optionsMock->expects($this->exactly(3))
                        ->method("getOption")
                        ->withConsecutive(array("rulesString"), array("rulesBirth"), array("antiRules"))
                        ->willReturn(null, null, null);
        }
        else
        {
            $optionsMock->expects($this->exactly(3))
                ->method("getOption")
                ->withConsecutive(array("rulesString"), array("rulesString"), array("antiRules"))
                ->willReturn($_rulesString, $_rulesString, null);
        }

        $rule = new CustomRule();

        if ($optionsMock instanceof Ulrichsg\Getopt)
        {
            if ($_expectedErrorMessage)
            {
                $expectedOutput = $_expectedErrorMessage;
                if ($_rulesString !== null) $expectedOutput .=  "Error: Unknown rules notation";

                $this->expectOutputRegex("/.*" . $expectedOutput . "/");
            }

            $rule->initialize($optionsMock);

            $this->assertEquals($_expectedRulesBirth, $rule->rulesBirth());
            $this->assertEquals($_expectedRulesStayAlive, $rule->rulesStayAlive());
        }
    }

    /**
     * DataProvider for CustomRuleTest::testCanParseRuleStrings().
     *
     * @return array Test values in the format array(rulesString, expectedRulesBirth, expectedRulesStayAlive, expectedErrorMessage)
     */
    public function parseRuleStringsProvider()
    {
        return array(

            // birth/stay-alive
            "Valid birth/stay-alive" => array("32/3", array(3), array(2, 3)),
            "Invalid birth/stay-alive: Missing second number" => array("23/", array(), array(), "Error: The custom rule must have at least 1 birth condition and 1 stay alive condition\n"),
            "Invalid birth/stay-alive: Missing first number" => array("/3", array(), array(), "Error: The custom rule must have at least 1 birth condition and 1 stay alive condition\n"),
            "Invalid birth/stay-alive: Missing both numbers" => array("/", array(), array(), "Error: The custom rule must have at least 1 birth condition and 1 stay alive condition\n"),
            "Invalid birth/stay-alive: Having 3 numbers" => array("23/3/3", array(), array(), "Error: The custom rule parts may not contain more than two number strings\n"),
            "Invalid birth/stay-alive: Numbers contain characters" => array("Hello3/4", array(), array(), "Error: The custom rule parts may only contain \"\/\" and numbers\n"),

            // stay-alive<G>birth/stay-alive
            "Valid stay-alive<G>birth/stay-alive" => array("2G4", array(4), array(2, 4)),
            "Invalid stay-alive<G>birth/stay-alive: Numbers contain characters" => array("2HelloG57", array(), array(), "Error: The custom rule parts may only contain \"G\" and numbers\n"),

            // empty rules string
            "" => array(null, array(), array(), "Error: Rules string is not set")
        );
    }

    /**
     * Checks whether the options --birthRules and --stayAliveRules work as expected.
     *
     * @param array $_returnValueMaps The return values for "getOption" in the format array("optionName", "returnValue")
     * @param array $_expectedRulesBirth The expected birth rules
     * @param array $_expectedRulesStayAlive The expected stay alive rules
     *
     * @dataProvider parseBirthStayAliveOptionsProvider()
     *
     * @covers \Rule\CustomRule::initialize()
     * @covers \Rule\CustomRule::getRulesFromNumericString()
     */
    public function testCanParseBirthStayAliveOptions(array $_returnValueMaps, array $_expectedRulesBirth, array $_expectedRulesStayAlive)
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();


        $optionsMock->expects($this->exactly(count($_returnValueMaps)))
                    ->method("getOption")
                    ->willReturnMap($_returnValueMaps);

        $rule = new CustomRule();

        if ($optionsMock instanceof \Ulrichsg\Getopt)
        {
            $rule->initialize($optionsMock);

            $this->assertEquals($_expectedRulesBirth, $rule->rulesBirth());
            $this->assertEquals($_expectedRulesStayAlive, $rule->rulesStayAlive());
        }
    }

    /**
     * DataProvider for CustomRuleTest::testCanParseBirthStayAliveOptions().
     *
     * @return array Test values in the format array(getopt values, expected birth rules, expected stay alive rules)
     */
    public function parseBirthStayAliveOptionsProvider()
    {
        return array(
            "Valid --rulesBirth and --rulesDeath 1" => array(
                array(
                    array("ruleString", null),
                    array("rulesBirth", "1234"),
                    array("rulesBirth", "1234"),
                    array("rulesStayAlive", "4567"),
                    array("rulesStayAlive", "4567"),
                    array("antiRules", null)
                ),
                array(1, 2, 3, 4),
                array(4, 5, 6, 7)
            ),
            "Valid --rulesBirth and --rulesDeath 2" => array(
                array(
                    array("ruleString", null),
                    array("rulesBirth", "2745"),
                    array("rulesBirth", "2745"),
                    array("rulesStayAlive", "3425"),
                    array("rulesStayAlive", "3425"),
                    array("antiRules", null)
                ),
                array(2, 4, 5, 7),
                array(2, 3, 4, 5)
            )
        );
    }
}
