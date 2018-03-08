<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\Field;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \GameOfLife\Field works as expected.
 */
class FieldTest extends TestCase
{
    /** @var Board $board A test board */
    private $board;

    public function setUp()
    {
        $this->board = new Board(3, 3, 1, true);
    }

    public function tearDown()
    {
        unset($this->board);
    }

    /**
     * Checks whether the default values are applied when constructing a Field.
     *
     * @covers \GameOfLife\Field::__construct()
     * @covers \GameOfLife\Field::parentBoard()
     * @covers \GameOfLife\Field::x()
     * @covers \GameOfLife\Field::y()
     * @covers \GameOfLife\Field::value()
     */
    public function testCanBeConstructed()
    {
        $field = new Field($this->board, 1, 2);

        $this->assertEquals($this->board, $field->parentBoard());
        $this->assertEquals(1, $field->x());
        $this->assertEquals(2, $field->y());
        $this->assertEquals(false, $field->value());
    }

    /**
     * Checks whether getters and setters work as expected.
     *
     * @dataProvider setAttributesProvider()
     * @covers \GameOfLife\Field::setParentBoard()
     * @covers \GameOfLife\Field::setX()
     * @covers \GameOfLife\Field::setY()
     * @covers \GameOfLife\Field::setValue()
     * @covers \GameOfLife\Field::parentBoard()
     * @covers \GameOfLife\Field::x()
     * @covers \GameOfLife\Field::y()
     * @covers \GameOfLife\Field::value()
     *
     * @param Board $_parentBoard The parent board
     * @param int $_x X-Coordinate of the field
     * @param int $_y Y-Coordinate of the field
     * @param bool $_value Status of the field (true = alive, false = dead)
     */
    public function testCanSetAttributes($_parentBoard, $_x, $_y, $_value)
    {
        $field = new Field($this->board, 1, 2);

        $field->setParentBoard($_parentBoard);
        $field->setX($_x);
        $field->setY($_y);
        $field->setValue($_value);

        $this->assertEquals($_parentBoard, $field->parentBoard());
        $this->assertEquals($_x, $field->x());
        $this->assertEquals($_y, $field->y());
        $this->assertEquals($_value, $field->value());
    }

    /**
     * DataProvider for FieldTest::testCanSetAttributes.
     */
    public function setAttributesProvider()
    {
        return array(
            array($this->board, 1, 2, true),
            array($this->board, 2, 3, false),
            array($this->board, 4, 5, true)
        );
    }

    /**
     * Checks whether the Field correctly returns its status.
     *
     * @covers \GameOfLife\Field::isAlive()
     * @covers \GameOfLife\Field::isDead()
     */
    public function testCanReturnStatus()
    {
        $field = new Field($this->board, 0, 0);

        $field->setValue(true);
        $this->assertTrue($field->isAlive());
        $this->assertFalse($field->isDead());

        $field->setValue(false);
        $this->assertFalse($field->isAlive());
        $this->assertTrue($field->isDead());
    }

    /**
     * Checks whether the Field can calculate the amount of living and dead neighbor fields.
     *
     * @covers \GameOfLife\Field::numberOfDeadNeighbors()
     * @covers \GameOfLife\Field::numberOfLivingNeighbors()
     * @dataProvider sumNeighborStatusesProvider
     *
     * @param int[] $_fieldCoordinates Coordinates of the field whose neighbours will be checked
     * @param int[][] $_livingNeighborsCoordinates Coordinates of the living neighbor cells
     * @param int $_amountLivingNeighbors Expected amount of living neighbors
     * @param int $_amountDeadNeighbors Expected amount of dead neighbors
     */
    public function testCanSumNeighborStatuses(array $_fieldCoordinates, array $_livingNeighborsCoordinates, int $_amountLivingNeighbors, int $_amountDeadNeighbors)
    {
        $field = new Field($this->board, $_fieldCoordinates["x"], $_fieldCoordinates["y"]);

        foreach ($_livingNeighborsCoordinates as $livingNeighborCoordinates)
        {
            $this->board->setField($livingNeighborCoordinates["x"], $livingNeighborCoordinates["y"], true);
        }

        $this->assertEquals($_amountLivingNeighbors, $field->numberOfLivingNeighbors());
        $this->assertEquals($_amountDeadNeighbors, $field->numberOfDeadNeighbors());
    }

    /**
     * DataProvider for FieldTest::testCanSumNeighborStatuses().
     */
    public function sumNeighborStatusesProvider()
    {
        return array(

            "3 living, 5 dead neighbors" => array(
                array("x" => 1, "y" => 1),
                array(
                    array("x" => 1, "y" => 2),
                    array("x" => 2, "y" => 1),
                    array("x" => 2, "y" => 2)
                ),
                3,
                5
            ),
            "1 living, 2 dead neighbors" => array(
                array("x" => 0, "y" => 0),
                array(
                    array("x" => 1, "y" => 0)
                ),
                1,
                2
            )
        );
    }
}
