<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\ResetOption;
use GameOfLife\Board;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Exit option works as expected.
 */
class ResetOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\ResetOption::__construct()
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new ResetOption($boardEditor);

        $this->assertEquals("reset", $option->name());
        $this->assertEquals("resetBoard", $option->callback());
        $this->assertEquals("Resets the edited board to an empty board", $option->description());
        $this->assertEquals(0, $option->numberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\ResetOption::resetBoard()
     */
    public function testCanExitBoardEditor()
    {
        $testBoard = new Board(4, 4, 4, true);
        $testBoard->setField(1, 1, true);
        $this->assertEquals(1, $testBoard->getAmountCellsAlive());

        $boardEditor = new BoardEditor("test", $testBoard);
        $option = new ResetOption($boardEditor);

        $expectedOutput = "╔════╗\n"
                        . "║    ║\n"
                        . "║    ║\n"
                        . "║    ║\n"
                        . "║    ║\n"
                        . "╚════╝\n";

        $this->expectOutputString($expectedOutput);
        $result = $option->resetBoard();
        $this->assertFalse($result);
        $this->assertEquals(0, $testBoard->getAmountCellsAlive());
    }
}
