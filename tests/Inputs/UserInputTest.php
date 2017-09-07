<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use GameOfLife\Board;
use GameOfLife\RuleSet;
use Input\UserInput;
use Ulrichsg\Getopt;

/**
 * Class UserInputTest
 */
class UserInputTest extends TestCase
{
    /** @var Userinput $input */
    private $input;
    /** @var Board $board */
    private $board;

    protected function setUp()
    {
        $this->input = new UserInput();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(2, 2, 50, true, $rules);
        $this->board->setField(0, 0, true);
    }

    protected function tearDown()
    {
        unset($this->input);
        unset($this->board);
    }

    public function testCanAddOptions()
    {
        $options = new Getopt();
        $this->input->addOptions($options);
        $optionList = $options->getOptionList();

        $this->assertEquals(1, count($optionList));
        $this->assertContains("edit", $optionList[0]);
    }

    public function testCanOutputBoard()
    {
        $expectedString= "\n" .
                         " --\n" .
                         "|o |\n" .
                         "|  |\n" .
                         " --\n";

        $this->expectOutputString($expectedString);
        $this->input->printBoardEditor($this->board);


        $expectedString .= "\n" .
                           " 0\n" .
                           " ---\n" .
                           "|X| |0\n" .
                           "|---|\n" .
                           "| | |\n" .
                           " ---\n";

        $this->expectOutputString($expectedString);
        $this->input->printBoardEditor($this->board, 0, 0);
    }
}