<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\SetWidthOption;
use Simulator\Board;
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
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new SetWidthOption($boardEditor);

        $this->assertEquals("width", $option->name());
        $this->assertEquals("setWidth", $option->callback());
        $this->assertEquals("Sets the board width", $option->description());
        $this->assertEquals(1, $option->getNumberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\SetWidthOption::setWidth()
     *
     * @throws \Exception
     */
    public function testCanSetWidth()
    {
        $testBoard = new Board(4, 4, true);
        $testBoard->setFieldState(1, 1, true);
        $this->assertEquals(1, $testBoard->getNumberOfLivingCells());

        $boardEditor = new BoardEditor("test", $testBoard);
        $option = new SetWidthOption($boardEditor);

        // Invalid width
        $this->expectOutputRegex("/.*/"); // Hide output

        $exceptionOccurred = false;
        try
        {
            $option->setWidth(0);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $this->assertEquals("The board width may not be less than 1.", $_exception->getMessage());
        }
        $this->assertTrue($exceptionOccurred);

        // Valid width, living cells still inside board
        $result = $option->setWidth(2);
        $this->assertEquals(1, $testBoard->getNumberOfLivingCells());
        $this->assertEquals(2, $testBoard->width());
        $this->assertFalse($result);

        // Valid width, living cells outside new board dimensions
        $result = $option->setWidth(1);
        $this->assertEquals(0, $testBoard->getNumberOfLivingCells());
        $this->assertEquals(1, $testBoard->width());
        $this->assertFalse($result);
    }
}
