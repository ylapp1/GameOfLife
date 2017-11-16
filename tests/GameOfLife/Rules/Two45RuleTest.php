<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Rule\Two45Rule;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Two45 class works as expected.
 */
class Two45RuleTest extends TestCase
{
    /**
     * Checks whether the birth and death rules are correctly set.
     */
    public function testCanBeConstructed()
    {
        $rule = new Two45Rule();

        $this->assertEquals(array(4, 5), $rule->rulesBirth());
        $this->assertEquals(array(2), $rule->rulesStayAlive());
    }
}