<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\RandomInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;

/**
 * Class BlinkerInputTest
 */
class RandomInputTest extends TestCase
{
    /** @var  RandomInput $input */
    private $input;

    protected function setUp()
    {
        $this->input = new RandomInput();
    }

    protected function tearDown()
    {
        unset($this->input);
    }

    public function testCanAddOptions()
    {
        $options = new Getopt();
        $this->input->addOptions($options);
        $optionList = $options->getOptionList();

        $this->assertEquals(1, count($optionList));
        $this->assertContains("fillPercent", $optionList[0]);
    }

    public function testCanSetCells()
    {
        $options = new Getopt();
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $board = new Board(10, 10, 50, true, $rules);

        $this->input->fillBoard($board, $options);

        $this->assertGreaterThan(0, $board->getAmountCellsAlive());
    }
}