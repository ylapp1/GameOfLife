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
use Ulrichsg\Getopt;
use Output\ConsoleOutput;

/**
 * Checks whether \Output\ConsoleOutput works as expected
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


    /**
     * @covers \Output\ConsoleOutput::startOutput()
     */
    public function testCanStartOutput()
    {
        $this->expectOutputString("\nStarting the simulation ...\n");
        $this->output->startOutput(new Getopt(),$this->board);
    }

    /**
     * @covers \Output\ConsoleOutput::outputBoard()
     */
    public function testCanOutputBoard()
    {
        $gameStepString = "Game step: 1";

        $padding = ceil(($this->board->width() - strlen($gameStepString)) / 2) + 1;
        $expectedPadding = "";

        for ($i = 0; $i < $padding; $i++)
        {
            $expectedPadding .= " ";
        }

        $board = "\n╔══╗" .
                 "\n║  ║" .
                 "\n║  ║" .
                 "\n╚══╝";
        $outputString = "/" . $expectedPadding . $gameStepString . ".*" . $board . "/";

        $this->expectOutputRegex($outputString);
        $this->output->outputBoard($this->board);
    }

    /**
     * @covers \Output\ConsoleOutput::finishOutput()
     */
    public function testCanFinishOutput()
    {
        $this->expectOutputString("\nSimulation finished. All cells are dead or a repeating pattern was detected.\n\n");
        $this->output->finishOutput();
    }
}