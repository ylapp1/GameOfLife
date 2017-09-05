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
use Output\ConsoleOutput;

/**
 * Class ConsoleOutputTest
 */
class ConsoleOutputTest extends TestCase
{
    /** @var Board $board*/
    private $board;
    /** @var ConsoleOutput $output */
    private $output;

    protected function setUp()
    {
        $this->output = new ConsoleOutput();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(2, 2, 50, true, $rules);
    }

    protected function tearDown()
    {
        unset($this->output);
        unset($this->board);
    }

    public function testCanOutputBoard()
    {
        $gameStepString = "Game step: 1";
        $board = "\n╔══╗" .
                 "\n║  ║" .
                 "\n║  ║" .
                 "\n╚══╝";
        $outputString = "/.*" . $gameStepString . ".*" . $board . "/";

        $this->expectOutputRegex($outputString);
        $this->output->outputBoard($this->board);
    }
}