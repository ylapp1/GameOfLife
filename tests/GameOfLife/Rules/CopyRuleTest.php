<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Rule\CopyRule;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the CopyRule class works as expected.
 */
class CopyRuleTest extends TestCase
{
    /**
     * Checks whether the birth and death rules are correctly set.
     */
    public function testCanBeConstructed()
    {
        $rule = new CopyRule();

        $this->assertEquals(array(1, 3, 5, 7), $rule->rulesBirth());
        $this->assertEquals(array(1, 3, 5, 7), $rule->rulesStayAlive());
    }
}