<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use Output\ConsoleOutput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

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

        $this->board = new Board(2, 2, 50, true);
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
        $reflectionProperty = $reflectionClass->getProperty("shellOutputHelper");
        $reflectionProperty->setAccessible(true);

        $this->assertInstanceOf(\Utils\Shell\ShellOutputHelper::class, $reflectionProperty->getValue($output));
    }

    /**
     * @covers \Output\ConsoleOutput::outputBoard()
     * @covers \Output\ConsoleOutput::getBoardContentString()
     * @covers \Output\ConsoleOutput::getRowOutputString()
     * @covers \Output\ConsoleOutput::getHorizontalLineString()
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

        $board = "\n *╔══╗" .
                 "\n *║  ║" .
                 "\n *║  ║" .
                 "\n *╚══╝";
        $outputString = "/ *" . $expectedPadding . $gameStepString . ".*" . $board . "/";

        $this->expectOutputRegex($outputString);
        $this->output->outputBoard($this->board, false);
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
