<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Input\BlinkerInput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\BlinkerInput works as expected
 */
class BlinkerInputTest extends TestCase
{
    /** @var BlinkerInput $input */
    private $input;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    protected function setUp()
    {
        $this->input = new BlinkerInput();
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
     * @covers \Input\BlinkerInput::__construct
     */
    public function testCanBeConstructed()
    {
        $input = new BlinkerInput();
        $this->assertEquals(1, $input->objectWidth());
        $this->assertEquals(3, $input->objectHeight());
        $this->assertEquals("blinker", $input->objectName());
    }

    /**
     * @dataProvider setCellsProvider
     * @covers \Input\BlinkerInput::fillBoard()
     *
     * @param int $_x            X-Coordinate of the cell
     * @param int $_y            Y-Coordinate of the cell
     * @param bool $_expected    Expected value of the cell
     */
    public function testCanSetCells($_x, $_y, $_expected)
    {
        $this->input->fillBoard($this->board, new Getopt());
        $this->assertEquals(3, $this->board->getAmountCellsAlive());
        $this->assertEquals($_expected, $this->board->getField($_x, $_y));
    }

    public function setCellsProvider()
    {
        return [
            "Cell 4|4" => [4, 4, true],
            "Cell 4|5" => [4, 5, true],
            "Cell 4|6" => [4, 6, true]
        ];
    }

    /**
     * @dataProvider fillBoardWithCustomPositionsProvider
     * @covers \Input\BlinkerInput::fillBoard()
     *
     * @param int $_blinkerPosX     X-Position of the top left corner of the blinker
     * @param int $_blinkerPosY     Y-Position of the top left corner of the blinker
     * @param bool $_expectsError   Expects error message
     */
    public function testCanFillBoardWithCustomPositions(int $_blinkerPosX, int $_blinkerPosY, bool $_expectsError)
    {
        $this->optionsMock->expects($this->exactly(4))
                          ->method("getOption")
                          ->withConsecutive(["blinkerPosX"], ["blinkerPosX"], ["blinkerPosY"], ["blinkerPosY"])
                          ->willReturn($_blinkerPosX, $_blinkerPosX, $_blinkerPosY, $_blinkerPosY);

        if ($_expectsError) $this->expectOutputString("Error: Blinker exceeds field borders.\n");

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

        if (! $_expectsError)
        {
            $this->assertEquals(3, $this->board->getAmountCellsAlive());
            $this->assertTrue($this->board->getField($_blinkerPosX, $_blinkerPosY));
            $this->assertTrue($this->board->getField($_blinkerPosX, $_blinkerPosY + 1));
            $this->assertTrue($this->board->getField($_blinkerPosX, $_blinkerPosY + 2));
        }
    }

    public function fillBoardWithCustomPositionsProvider()
    {
        return [
            "Exceed left border (-1|1)" => ["-1", "1", true],
            "Exceed upper border (1|-1)" => ["1", "-1", true],
            "Valid position (1|2)" => ["1", "2", false],
            "Valid position (2|4)" => ["2", "4", false],
            "Valid position (10|5)" => ["10", "5", true],
            "Exceed right border (11|4)" => ["11", "4", true],
            "Exceed bottom border (5|9)" => ["5", "9", true]
        ];
    }
}