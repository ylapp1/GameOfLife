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
            array(null, "rulesString", Getopt::REQUIRED_ARGUMENT, "CustomRule - A rules string in the format <stayAlive>/<birth> or <stayAlive>G<stayAlive/birth>"),
            array(null, "rulesBirth", Getopt::REQUIRED_ARGUMENT, "CustomRule - The numbers of living neighbor cells which will rebirth a dead cell as one string of digits"),
            array(null, "rulesStayAlive", Getopt::REQUIRED_ARGUMENT, "CustomRule - The numbers of living neighbor cells which will keep a living cell alive as one string of digits")
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
     * @covers \Rule\CustomRule::getRulesFromNumericString()
     */
    public function testCanParseRuleStrings($_rulesString = "", array $_expectedRulesBirth, array $_expectedRulesStayAlive, String $_expectedErrorMessage = "")
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        if (! $_rulesString)
        {
            if ($_expectedErrorMessage)
            {
                $optionsMock->expects($this->exactly(3))
                            ->method("getOption")
                            ->withConsecutive(array("rulesString"), array("rulesBirth"), array("rulesStayAlive"))
                            ->willReturn(null, null, null);
            }
            else
            {
                $optionsMock->expects($this->exactly(3))
                            ->method("getOption")
                            ->withConsecutive(array("rulesString"), array("rulesBirth"), array("antiRules"))
                            ->willReturn(null, null, null);
            }
        }
        else
        {
            if ($_expectedErrorMessage)
            {
                $optionsMock->expects($this->exactly(1))
                            ->method("getOption")
                            ->withConsecutive(array("rulesString"))
                            ->willReturn($_rulesString);
            }
            else
            {
                $optionsMock->expects($this->exactly(2))
                            ->method("getOption")
                            ->withConsecutive(array("rulesString"), array("antiRules"))
                            ->willReturn($_rulesString, null);
            }
        }

        $rule = new CustomRule();

        // Hide output
        $this->expectOutputRegex("/.*/");

        if ($optionsMock instanceof Ulrichsg\Getopt)
        {
            $exceptionOccurred = false;
            try
            {
                $rule->initialize($optionsMock);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals($_expectedErrorMessage, $_exception->getMessage());
            }

            if ($_expectedErrorMessage) $this->assertTrue($exceptionOccurred);
            else
            {
                $this->assertFalse($exceptionOccurred);
                $this->assertEquals($_expectedRulesBirth, $rule->rulesBirth());
                $this->assertEquals($_expectedRulesStayAlive, $rule->rulesStayAlive());
            }
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
            "Valid birth/stay-alive: Both rule parts set" => array("32/3", array(3), array(2, 3)),
            "Valid birth/stay-alive: Missing first number" => array("/3", array(3), array()),
            "Valid birth/stay-alive: Missing second number" => array("23/", array(), array(2, 3)),
            "Invalid birth/stay-alive: Missing both numbers" => array("/", array(), array(), "The rules string must contain at least 1 set rule part."),
            "Invalid birth/stay-alive: Having 3 numbers" => array("23/3/3", array(), array(), "Unknown rules format."),
            "Invalid birth/stay-alive: Numbers contain characters" => array("Hello3/4", array(), array(), "Unknown rules format."),

            // stay-alive<G>birth/stay-alive
            "Valid stay-alive<G>birth/stay-alive: Both rule parts set" => array("2G4", array(4), array(2, 4)),
            "Valid stay-alive<G>birth/stay-alive: Missing first number" => array("G45", array(4, 5), array(4, 5)),
            "Valid stay-alive<G>birth/stay-alive: Missing second number" => array("35G", array(), array(3, 5)),
            "Invalid stay-alive<G>birth/stay-alive: Numbers contain characters" => array("2HelloG57", array(), array(), "Unknown rules format."),
            "Invalid stay--alive<G>birth/stay-alive: Missing both numbers" => array("G", array(), array(), "The rules string must contain at least 1 set rule part."),
            "Invalid stay-alive<G>birth/stay-alive: Having 3 numbers" => array("1G2G3", array(), array(), "Unknown rules format."),

            // empty rules string
            "Empty rules string" => array("", array(), array(), "No rules specified.")
        );
    }

    /**
     * Checks whether the options --birthRules and --stayAliveRules work as expected.
     *
     * @param array $_returnValueMaps The return values for "getOption" in the format array("optionName", "returnValue")
     * @param array $_expectedRulesBirth The expected birth rules
     * @param array $_expectedRulesStayAlive The expected stay alive rules
     *
     * @throws \Exception
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
                    array("rulesStayAlive", "3425"),
                    array("antiRules", null)
                ),
                array(2, 4, 5, 7),
                array(2, 3, 4, 5)
            )
        );
    }
}
