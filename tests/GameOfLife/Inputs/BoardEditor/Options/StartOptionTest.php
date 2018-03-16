<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\StartOption;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Start option works as expected.
 */
class StartOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\StartOption::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new StartOption($boardEditor);

        $this->assertEquals("start", $option->name());
        $this->assertEquals("startSimulation", $option->callback());
        $this->assertEquals("Starts the simulation", $option->description());
        $this->assertEquals(0, $option->numberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\StartOption::startSimulation()
     *
     * @throws \Exception
     */
    public function testCanStartSimulation()
    {
        $boardEditor = new BoardEditor("test");
        $option = new StartOption($boardEditor);

        $result = $option->startSimulation();
        $this->assertTrue($result);
    }
}
