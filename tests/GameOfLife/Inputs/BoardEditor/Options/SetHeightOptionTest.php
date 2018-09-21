<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\SetHeightOption;
use Simulator\Board;
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
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new SetHeightOption($boardEditor);

        $this->assertEquals("height", $option->name());
        $this->assertEquals("setHeight", $option->callback());
        $this->assertEquals("Sets the board height", $option->description());
        $this->assertEquals(1, $option->getNumberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\SetHeightOption::setHeight()
     *
     * @throws \Exception
     */
    public function testCanSetWidth()
    {
        $testBoard = new Board(4, 4, true);
        $testBoard->setFieldState(1, 1, true);
        $this->assertEquals(1, $testBoard->getNumberOfLivingCells());

        $boardEditor = new BoardEditor("test", $testBoard);
        $option = new SetHeightOption($boardEditor);

        // Invalid height

        $this->expectOutputRegex("/.*/"); // Hide output

        $exceptionOccurred = false;
        try
        {
            $option->setHeight(0);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $this->assertEquals("The board height may not be less than 1.", $_exception->getMessage());
        }
        $this->assertTrue($exceptionOccurred);

        // Valid width, living cells still inside board
        $result = $option->setHeight(2);
        $this->assertEquals(1, $testBoard->getNumberOfLivingCells());
        $this->assertEquals(2, $testBoard->height());
        $this->assertFalse($result);

        // Valid width, living cells outside new board dimensions
        $result = $option->setHeight(1);
        $this->assertEquals(0, $testBoard->getNumberOfLivingCells());
        $this->assertEquals(1, $testBoard->height());
        $this->assertFalse($result);
    }
}
