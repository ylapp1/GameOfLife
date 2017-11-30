<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Rule\ConwayRule;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Rule\ComwayRule works as expected
 */
class ComwayRuleTest extends TestCase
{
    /**
     * Checks whether the birth and stay alive rules are correctly set.
     */
    public function testCanBeConstructed()
    {
        $rule = new ConwayRule();

        $this->assertEquals(array(3), $rule->rulesBirth());
        $this->assertEquals(array(2, 3), $rule->rulesStayAlive());
    }
}