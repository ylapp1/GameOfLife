<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Field;
use Rule\BaseRule;
use Rule\ConwayRule;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Rule\BaseRule works as expected.
 */
class BaseRuleTest extends TestCase
{
    /**
     * Checks whether getters and setters work as expected.
     *
     * @dataProvider setAttributesProvider
     * @covers \Rule\BaseRule::rulesBirth()
     * @covers \Rule\BaseRule::setRulesBirth()
     * @covers \Rule\BaseRule::rulesStayAlive
     * @covers \Rule\BaseRule::setRulesStayAlive()
     *
     * @param int[] $_rulesBirth Birth rules
     * @param int[] $_rulesStayAlive Stay alive rules
     */
    public function testCanSetAttributes(array $_rulesBirth, array $_rulesStayAlive)
    {
        $rule = new BaseRule();

        $rule->setRulesBirth($_rulesBirth);
        $rule->setRulesStayAlive($_rulesStayAlive);

        $this->assertEquals($_rulesBirth, $rule->rulesBirth());
        $this->assertEquals($_rulesStayAlive, $rule->rulesStayAlive());
    }

    /**
     * DataProvider for BaseRuleTest::testCanSetAttributes()
     *
     * @return int[][][] Test values
     */
    public function setAttributesProvider(): array
    {
        return array(
            array(
                array(0, 1, 2, 3),
                array(2, 3, 4, 5)
            ),
            array(
                array(4, 5, 6, 7, 8, 9),
                array(1, 2, 3, 4, 5, 6)
            )
        );
    }

    /**
     * Checks whether the birth/stay alive rules are correctly applied.
     *
     * Tests all possible combinations of living/dead neighbors with the comway rules
     *
     * @dataProvider calculateNewStateProvider
     *
     * @param bool $_initialState Initial state of the field
     * @param int $_amountLivingNeighbors Amount of living neighbors
     * @param bool $_expectedState Expected new state of the field
     */
    public function testCanCalculateNewState(bool $_initialState, int $_amountLivingNeighbors, bool $_expectedState)
    {
        $fieldMock = $this->getMockBuilder(\GameOfLife\Field::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        $fieldMock->expects($this->exactly(1))
                  ->method("isAlive")
                  ->willReturn($_initialState);

        $fieldMock->expects($this->exactly(1))
                  ->method("numberOfLivingNeighbors")
                  ->willReturn($_amountLivingNeighbors);

        if ($_initialState == $_expectedState && $_initialState == false)
        { // If cell state won't change

            $fieldMock->expects($this->exactly(1))
                      ->method("value")
                      ->willReturn($_initialState);
        }

        $rule = new ConwayRule();

        if ($fieldMock instanceof Field)
        {
            $newState = $rule->calculateNewState($fieldMock);
            $this->assertEquals($_expectedState, $newState);
        }
    }

    /**
     * DataProvider for BaseRuleTest::testCanCalculateNewState().
     *
     * @return array Test values
     */
    public function calculateNewStateProvider(): array
    {
        return array(

            // Cell alive
            "Cell alive, 0 living neighbors" => array(true, 0, false),
            "Cell alive, 1 living neighbor" => array(true, 1, false),
            "Cell alive, 2 living neighbors" => array(true, 2, true),
            "Cell alive, 3 living neighbors" => array(true, 3, true),
            "Cell alive, 4 living neighbors" => array(true, 4, false),
            "Cell alive, 5 living neighbors" => array(true, 5, false),
            "Cell alive, 6 living neighbors" => array(true, 6, false),
            "Cell alive, 7 living neighbors" => array(true, 7, false),
            "Cell alive, 8 living neighbors" => array(true, 8, false),

            // Cell dead
            "Cell dead, 0 living neighbors" => array(false, 0, false),
            "Cell dead, 1 living neighbor" => array(false, 1, false),
            "Cell dead, 2 living neighbors" => array(false, 2, false),
            "Cell dead, 3 living neighbors" => array(false, 3, true),
            "Cell dead, 4 living neighbors" => array(false, 4, false),
            "Cell dead, 5 living neighbors" => array(false, 5, false),
            "Cell dead, 6 living neighbors" => array(false, 6, false),
            "Cell dead, 7 living neighbors" => array(false, 7, false),
            "Cell dead, 8 living neighbors" => array(false, 8, false),
        );
    }
}