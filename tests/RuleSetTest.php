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
 * Class RuleSetTest
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
     * @covers \GameOfLife\RuleSet::birth()
     * @covers \GameOfLife\RuleSet::death()
     */
    public function testCanGetAttributes()
    {
        $this->assertEquals(array(1), $this->ruleSet->birth());
        $this->assertEquals(array(2), $this->ruleSet->death());
    }

    /**
     * @covers \GameOfLife\RuleSet::setBirth()
     * @covers \GameOfLife\RuleSet::setDeath()
     * @dataProvider setAttributesProvider
     *
     * @param array $_rulesBirth    Birth rules
     * @param array $_rulesDeath    Death rules
     */
    public function testCanSetAttributes($_rulesBirth, $_rulesDeath)
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
