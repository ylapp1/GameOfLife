<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Simulator\Board;
use Output\ConsoleOutput;
use PHPUnit\Framework\TestCase;
use Ulrichsg\Getopt;

/**
 * Checks whether \Output\ConsoleOutput works as expected.
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
        $this->board = new Board(2, 2, true);

        $this->expectOutputRegex("/GAME OF LIFE\n *CONSOLE OUTPUT/");
	    $this->output->startOutput(new Getopt(), $this->board);
    }

    protected function tearDown()
    {
        unset($this->output);
        unset($this->board);
    }


    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \Output\ConsoleOutput::__construct()
     *
     * @throws ReflectionException
     */
    public function testCanBeConstructed()
    {
        $output = new ConsoleOutput();

        $reflectionClass = new ReflectionClass(\Output\ConsoleOutput::class);
        $reflectionProperty = $reflectionClass->getProperty("shellOutputFormatter");
        $reflectionProperty->setAccessible(true);

        $this->assertInstanceOf(\Util\Shell\ShellOutputFormatter::class, $reflectionProperty->getValue($output));
    }

    /**
     * @covers \Output\ConsoleOutput::outputBoard()
     */
    public function testCanOutputBoard()
    {
        $gameStepString = "Game step: 1";

        $boardString = "\n *╔══╗" .
                 "\n *║  ║" .
                 "\n *║  ║" .
                 "\n *╚══╝";
        $outputString = "/ *" . $gameStepString . "\n*" . $boardString . "/";

        $this->expectOutputRegex($outputString);
        $this->output->outputBoard($this->board, 1);
    }

    /**
     * Checks whether the output can be finished as expected.
     *
     * @covers \Output\BaseOutput::finishOutput()
     */
    public function testCanFinishOutput()
    {
        $this->expectOutputRegex("/.*Simulation finished: All cells are dead\..*/");
        $this->output->finishOutput("All cells are dead");
    }
}
