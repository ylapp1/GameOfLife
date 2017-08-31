<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\BlinkerInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;


/**
 * Class BlinkerInputTest
 */
class BlinkerInputTest extends TestCase
{
    /** @var  BlinkerInput $input */
    private $input;

    protected function setUp()
    {
        $this->input = new BlinkerInput();
    }

    protected function tearDown()
    {
        unset($this->input);
    }

    public function testCanAddOptions()
    {
        $options = new Getopt();

        $this->input->addOptions($options);
        $this->assertEquals(2, count($options->getOptionList()));
    }

    public function testCanSetCells()
    {
        $options = new Getopt();
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $board = new Board(10, 10, 50, true, $rules);

        $this->input->fillBoard($board, $options);

        $this->assertEquals(true, $board->getField(4, 4));
        $this->assertEquals(true, $board->getField(4, 5));
        $this->assertEquals(true, $board->getField(4, 6));
    }
}