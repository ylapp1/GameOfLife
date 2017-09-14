<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\GliderInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;

/**
 * Class GliderInputTest
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
    }

    /**
     * @covers \Input\GliderInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $gliderOptions = array(
            array(null, "gliderPosX", Getopt::REQUIRED_ARGUMENT, "X position of the glider"),
            array(null, "gliderPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the glider")
        );

        $this->optionsMock->expects($this->exactly(1))
            ->method("addOptions")
            ->with($gliderOptions);
        $this->input->addOptions($this->optionsMock);
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
        $this->optionsMock->method("getOption")
            ->withConsecutive(["gliderPosX"], ["gliderPosY"])
            ->willReturn($_gliderPosX, $_gliderPosY);

        if ($_expectsError) $this->expectOutputString("Error: Glider exceeds field borders.");

        $this->input->fillBoard($this->board, $this->optionsMock);

        if (! $_expectsError)
        {
            $this->assertEquals(true, $this->board->getField($_gliderPosX, $_gliderPosY - 1));
            $this->assertEquals(true, $this->board->getField($_gliderPosX + 1, $_gliderPosY + 1));
            $this->assertEquals(true, $this->board->getField($_gliderPosX - 1, $_gliderPosY + 1));
            $this->assertEquals(true, $this->board->getField($_gliderPosX, $_gliderPosY + 1));
            $this->assertEquals(true, $this->board->getField($_gliderPosX + 1, $_gliderPosY + 1));
        }
    }

    public function fillBoardWithCustomPositionsProvider()
    {
        return [
            "Exceed left border (0|1)" => [0, 1, true],
            "Exceed upper border (1|0)" => [1, 0, true],
            "Valid position (1|2)" => [1, 2, false],
            "Valid position (2|4)" => [2, 4, false],
            "Valid position (6|5)" => [6, 5, false],
            "Exceed right border (9|4)" => [9, 4, true],
            "Exceed bottom border (5|9)" => [5, 9, true]
        ];
    }
}