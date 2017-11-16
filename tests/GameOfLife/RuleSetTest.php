<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\RuleSet;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \GameOfLife\RuleSet works as expected.
 */
class RuleSetTest extends TestCase
{
    /** @var RuleSet */
    private $ruleSet;

    protected function setUp()
    {
        $this->ruleSet = new RuleSet(array(1), array(2));
    }

    public function tearDown()
    {
        unset($this->ruleSet);
    }

    /**
     * @covers \GameOfLife\RuleSet::__construct()
     */
    public function testCanBeConstructed()
    {
        $ruleSet = new RuleSet(array(0), array(0, 1));
        $this->assertEquals(array(0), $ruleSet->birth());
        $this->assertEquals(array(0, 1), $ruleSet->death());
    }

    /**
     * @dataProvider setAttributesProvider
     * @covers \GameOfLife\RuleSet::setBirth()
     * @covers \GameOfLife\RuleSet::birth()
     * @covers \GameOfLife\RuleSet::setDeath()
     * @covers \GameOfLife\RuleSet::death()
     *
     * @param array $_rulesBirth    Birth rules
     * @param array $_rulesDeath    Death rules
     */
    public function testCanSetAttributes(array $_rulesBirth, array $_rulesDeath)
    {
        $this->ruleSet->setBirth($_rulesBirth);
        $this->ruleSet->setDeath($_rulesDeath);

        $this->assertEquals($_rulesBirth, $this->ruleSet->birth());
        $this->assertEquals($_rulesDeath, $this->ruleSet->death());
    }

    public function setAttributesProvider()
    {
        return [
            [[1, 2], [4, 5]],
            [[4, 6, 7, 4], [1, 2, 3]],
            [[1, 2, 3, 4, 5, 6 , 7, 8], [9]]
        ];
    }
}
