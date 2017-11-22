<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\SetHeightOption;
use GameOfLife\Board;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Height option works as expected.
 */
class SetHeightOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\SetHeightOption::__construct()
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new SetHeightOption($boardEditor);

        $this->assertEquals("height", $option->name());
        $this->assertEquals("setHeight", $option->callback());
        $this->assertEquals("Sets the board height", $option->description());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\SetHeightOption::setHeight()
     */
    public function testCanSetWidth()
    {
        $testBoard = new Board(4, 4, 4, true);
        $testBoard->setField(1, 1, true);
        $this->assertEquals(1, $testBoard->getAmountCellsAlive());

        $boardEditor = new BoardEditor("test", $testBoard);
        $option = new SetHeightOption($boardEditor);

        // Invalid heught
        $this->expectOutputRegex("/Error: the board height may not be less than 1\n.*/");
        $result = $option->setHeight(0);
        $this->assertFalse($result);

        // Valid width, living cells still inside board
        $result = $option->setHeight(2);
        $this->assertEquals(1, $testBoard->getAmountCellsAlive());
        $this->assertEquals(2, $testBoard->height());
        $this->assertFalse($result);

        // Valid width, living cells outside new board dimensions
        $result = $option->setHeight(1);
        $this->assertEquals(0, $testBoard->getAmountCellsAlive());
        $this->assertEquals(1, $testBoard->height());
        $this->assertFalse($result);
    }
}