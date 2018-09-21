<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\ExitOption;
use Simulator\Board;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Exit option works as expected.
 */
class ExitOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\ExitOption::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new ExitOption($boardEditor);

        $this->assertEquals("exit", $option->name());
        $this->assertEquals("exitBoardEditor", $option->callback());
        $this->assertEquals("Exit the application", $option->description());
        $this->assertEquals(0, $option->getNumberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\ExitOption::exitBoardEditor()
     *
     * @throws \Exception
     */
    public function testCanExitBoardEditor()
    {
        $testBoard = new Board(4, 4, true);
        $testBoard->setFieldState(1, 1, true);
        $this->assertEquals(1, $testBoard->getNumberOfLivingCells());

        $boardEditor = new BoardEditor("test", $testBoard);
        $option = new ExitOption($boardEditor);

        $result = $option->exitBoardEditor();
        $this->assertTrue($result);
        $this->assertEquals(0, $testBoard->getNumberOfLivingCells());
    }
}
