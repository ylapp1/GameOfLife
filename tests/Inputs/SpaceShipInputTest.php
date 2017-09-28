<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Input\SpaceShipInput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\SpaceShipInput works as expected
 */
class SpaceShipInputTest extends TestCase
{
    /** @var SpaceShipInput $input */
    private $input;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    protected function setUp()
    {
        $this->input = new SpaceShipInput();
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
     * @covers \Input\SpaceShipInput::__construct
     */
    public function testCanBeConstructed()
    {
        $input = new SpaceShipInput();
        $this->assertEquals(5, $input->objectWidth());
        $this->assertEquals(4, $input->objectHeight());
        $this->assertEquals("spaceShip", $input->objectName());
    }

    /**
     * @dataProvider setCellsProvider
     *
     * @param int $_x            X-Coordinate of the cell
     * @param int $_y            Y-Coordinate of the cell
     * @param bool $_expected    Expected value of the cell
     */
    public function testCanSetCells(int $_x, int $_y, bool $_expected)
    {
        $this->input->fillBoard($this->board, new Getopt());

        $this->assertEquals(9, $this->board->getAmountCellsAlive());
        $this->assertEquals($_expected, $this->board->getField($_x, $_y));
    }

    public function setCellsProvider()
    {
        return [
            "Cell 5|4" => [5, 4, true],
            "Cell 6|4" => [6, 4, true],
            "Cell 7|4" => [7, 4, true],
            "Cell 8|4" => [8, 4, true],
            "Cell 4|5" => [4, 5, true],
            "Cell 8|5" => [8, 5, true],
            "Cell 8|6" => [8, 6, true],
            "Cell 4|7" => [4, 7, true],
            "Cell 7|7" => [7, 7, true]
        ];
    }

    /**
     * @dataProvider fillBoardWithCustomPositionsProvider
     * @covers \Input\SpaceShipInput::fillBoard()
     *
     * @param int $_spaceShipPosX     X-Position of the top left corner of the spaceship
     * @param int $_spaceShipPosY     Y-Position of the top left corner of the spaceship
     * @param bool $_expectsError     True: Expects error message
     *                                False: Expects no error message
     */
    public function testCanFillBoardWithCustomPositions(int $_spaceShipPosX, int $_spaceShipPosY, bool $_expectsError)
    {
        $this->optionsMock->expects($this->exactly(4))
                          ->method("getOption")
                          ->withConsecutive(["spaceShipPosX"], ["spaceShipPosX"], ["spaceShipPosY"], ["spaceShipPosY"])
                          ->willReturn($_spaceShipPosX, $_spaceShipPosX, $_spaceShipPosY, $_spaceShipPosY);

        if ($_expectsError) $this->expectOutputString("Error: Spaceship exceeds field borders.\n");

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

        if (! $_expectsError)
        {
            $this->assertEquals(9, $this->board->getAmountCellsAlive());
            $this->assertTrue($this->board->getField($_spaceShipPosX + 1, $_spaceShipPosY));
            $this->assertTrue($this->board->getField($_spaceShipPosX + 2, $_spaceShipPosY));
            $this->assertTrue($this->board->getField($_spaceShipPosX + 3, $_spaceShipPosY));
            $this->assertTrue($this->board->getField($_spaceShipPosX + 4, $_spaceShipPosY));
            $this->assertTrue($this->board->getField($_spaceShipPosX, $_spaceShipPosY + 1));
            $this->assertTrue($this->board->getField($_spaceShipPosX + 4, $_spaceShipPosY + 1));
            $this->assertTrue($this->board->getField($_spaceShipPosX + 4, $_spaceShipPosY + 2));
            $this->assertTrue($this->board->getField($_spaceShipPosX, $_spaceShipPosY + 3));
            $this->assertTrue($this->board->getField($_spaceShipPosX + 3, $_spaceShipPosY + 3));
        }
    }

    public function fillBoardWithCustomPositionsProvider()
    {
        return [
            "Exceed left border (-1|1)" => ["-1", "1", true],
            "Exceed upper border (1|-1)" => ["1", "-1", true],
            "Valid position (1|2)" => ["1", "2", false],
            "Valid position (2|4)" => ["2", "4", false],
            "Valid position (5|5)" => ["5", "5", false],
            "Exceed right border (7|4)" => ["7", "4", true],
            "Exceed bottom border (3|9)" => ["3", "9", true]
        ];
    }
}