<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Rule\ComwayRule;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Rule\ComwayRule works as expected
 */
class ComwayRuleTest extends TestCase
{
    /**
     * Checks whether the birth and death rules are correctly set
     */
    public function testCanBeConstructed()
    {
        $rule = new ComwayRule();

        $this->assertEquals(array(3), $rule->rulesBirth());
        $this->assertEquals(array(0, 1, 4, 5, 6, 7, 8), $rule->rulesDeath());
    }
}