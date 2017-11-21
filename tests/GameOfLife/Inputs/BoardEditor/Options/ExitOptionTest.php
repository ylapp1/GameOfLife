<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\ExitOption;
use GameOfLife\Board;
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
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new ExitOption($boardEditor);

        $this->assertEquals("exit", $option->name());
        $this->assertEquals("exitBoardEditor", $option->callback());
        $this->assertEquals("Exit the application", $option->description());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\ExitOption::exitBoardEditor()
     */
    public function testCanExitBoardEditor()
    {
        $testBoard = new Board(4, 4, 4, true);
        $testBoard->setField(1, 1, true);
        $this->assertEquals(1, $testBoard->getAmountCellsAlive());

        $boardEditor = new BoardEditor("test", $testBoard);
        $option = new ExitOption($boardEditor);

        $result = $option->exitBoardEditor();
        $this->assertTrue($result);
        $this->assertEquals(0, $testBoard->getAmountCellsAlive());
    }
}