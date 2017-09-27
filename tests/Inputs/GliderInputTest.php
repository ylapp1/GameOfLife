<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Input\GliderInput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\GliderInput works as expected
 */
class GliderInputTest extends TestCase
{
    /** @var GliderInput $input */
    private $input;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    protected function setUp()
    {
        $this->input = new GliderInput();
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);
        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();
    }

    protected function tearDown()
    {
        unset($this->input);
        unset($this->board);
        unset($this->optionsMock);
    }

    /**
     * @covers \Input\GliderInput::__construct
     */
    public function testCanBeConstructed()
    {
        $input = new GliderInput();
        $this->assertEquals(3, $input->objectWidth());
        $this->assertEquals(3, $input->objectHeight());
        $this->assertEquals("glider", $input->objectName());
    }

    /**
     * @dataProvider setCellsProvider
     * @covers \Input\GliderInput::fillBoard()
     *
     * @param int $_x            X-Coordinate of the cell
     * @param int $_y            Y-Coordinate of the cell
     * @param bool $_expected    Expected value of the cell
     */
    public function testCanSetCells(int $_x, int $_y, bool $_expected)
    {
        $options = new Getopt();
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $board = new Board(10, 10, 50, true, $rules);

        $this->input->fillBoard($board, $options);

        $this->assertEquals(5, $board->getAmountCellsAlive());
        $this->assertEquals($_expected, $board->getField($_x, $_y));
    }

    public function setCellsProvider()
    {
        return [
            "Cell 5|4" => [5, 4, true],
            "Cell 6|5" => [6, 5, true],
            "Cell 4|6" => [4, 6, true],
            "Cell 5|6" => [5, 6, true],
            "Cell 6|6" => [6, 6, true]
        ];
    }

    /**
     * @dataProvider fillBoardWithCustomPositionsProvider
     * @covers \Input\GliderInput::fillBoard()
     *
     * @param int $_gliderPosX     X-Position of the top left corner of the glider
     * @param int $_gliderPosY     Y-Position of the top left corner of the glider
     * @param bool $_expectsError   Expects error message
     */
    public function testCanFillBoardWithCustomPositions(int $_gliderPosX, int $_gliderPosY, bool $_expectsError)
    {
        $this->optionsMock->expects($this->exactly(4))
                          ->method("getOption")
                          ->withConsecutive(["gliderPosX"], ["gliderPosX"], ["gliderPosY"], ["gliderPosY"])
                          ->willReturn($_gliderPosX, $_gliderPosX, $_gliderPosY, $_gliderPosY);

        if ($_expectsError) $this->expectOutputString("Error: Glider exceeds field borders.\n");

        $this->input->fillBoard($this->board, $this->optionsMock);

        if (! $_expectsError)
        {
            $this->assertEquals(5, $this->board->getAmountCellsAlive());
            $this->assertTrue($this->board->getField($_gliderPosX + 1, $_gliderPosY));
            $this->assertTrue($this->board->getField($_gliderPosX + 2, $_gliderPosY + 2));
            $this->assertTrue($this->board->getField($_gliderPosX, $_gliderPosY + 2));
            $this->assertTrue($this->board->getField($_gliderPosX + 1, $_gliderPosY + 2));
            $this->assertTrue($this->board->getField($_gliderPosX + 2, $_gliderPosY + 2));
        }
    }

    public function fillBoardWithCustomPositionsProvider()
    {
        return [
            "Exceed left border (-1|1)" => ["-1","1", true],
            "Exceed upper border (1|-1)" => ["1", "-1", true],
            "Valid position (1|2)" => ["1", "2", false],
            "Valid position (2|4)" => ["2", "4", false],
            "Valid position (6|5)" => ["6", "5", false],
            "Exceed right border (9|4)" => ["9", "4", true],
            "Exceed bottom border (5|9)" => ["5", "9", true]
        ];
    }
}