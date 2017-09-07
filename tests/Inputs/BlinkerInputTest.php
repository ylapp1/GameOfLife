<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\BlinkerInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;

/**
 * Class BlinkerInputTest
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
    }

    /**
     * @covers \Input\BlinkerInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $blinkerOptions = array(
            array(null, "blinkerPosX", Getopt::REQUIRED_ARGUMENT, "X position of the blinker"),
            array(null, "blinkerPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the blinker"));

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($blinkerOptions);

        $this->input->addOptions($this->optionsMock);
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
        $this->optionsMock->method("getOption")
                          ->withConsecutive(["blinkerPosX"], ["blinkerPosY"])
                          ->willReturn($_blinkerPosX, $_blinkerPosY);

        if ($_expectsError) $this->expectOutputString("Error: Blinker exceeds field borders.");

        $this->input->fillBoard($this->board, $this->optionsMock);

        if (! $_expectsError)
        {
            $this->assertEquals(true, $this->board->getField($_blinkerPosX - 1, $_blinkerPosY - 1));
            $this->assertEquals(true, $this->board->getField($_blinkerPosX - 1, $_blinkerPosY));
            $this->assertEquals(true, $this->board->getField($_blinkerPosX - 1, $_blinkerPosY + 1));
            $this->assertEquals(false, $this->board->getField($_blinkerPosX + 3, $_blinkerPosY));
        }
    }

    public function fillBoardWithCustomPositionsProvider()
    {
        return [
            "Exceed left border (0|1)" => [0, 1, true],
            "Exceed upper border (1|0)" => [1, 0, true],
            "Valid position (1|2)" => [1, 2, false],
            "Valid position (2|4)" => [2, 4, false],
            "Valid position (10|5)" => [10, 5, false],
            "Exceed right border (11|4)" => [11, 4, true],
            "Exceed bottom border (5|9)" => [5, 9, true]
        ];
    }
}