<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\SetWidthOption;
use GameOfLife\Board;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Width option works as expected.
 */
class SetWidthOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\SetWidthOption::__construct()
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new SetWidthOption($boardEditor);

        $this->assertEquals("width", $option->name());
        $this->assertEquals("setWidth", $option->callback());
        $this->assertEquals("Sets the board width", $option->description());
        $this->assertEquals(1, $option->numberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\SetWidthOption::setWidth()
     */
    public function testCanSetWidth()
    {
        $testBoard = new Board(4, 4, 4, true);
        $testBoard->setField(1, 1, true);
        $this->assertEquals(1, $testBoard->getAmountCellsAlive());

        $boardEditor = new BoardEditor("test", $testBoard);
        $option = new SetWidthOption($boardEditor);

        // Invalid width
        $this->expectOutputRegex("/Error: The board width may not be less than 1\n.*/");
        $result = $option->setWidth(0);
        $this->assertFalse($result);

        // Valid width, living cells still inside board
        $result = $option->setWidth(2);
        $this->assertEquals(1, $testBoard->getAmountCellsAlive());
        $this->assertEquals(2, $testBoard->width());
        $this->assertFalse($result);

        // Valid width, living cells outside new board dimensions
        $result = $option->setWidth(1);
        $this->assertEquals(0, $testBoard->getAmountCellsAlive());
        $this->assertEquals(1, $testBoard->width());
        $this->assertFalse($result);
    }
}