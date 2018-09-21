<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Simulator\Board;
use Utils\Geometry\Coordinate;
use Simulator\Field;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Simulator\Field works as expected.
 */
class FieldTest extends TestCase
{
    /** @var Board $board A test board */
    private $board;

    public function setUp()
    {
        $this->board = new Board(3, 3, true);
    }

    public function tearDown()
    {
        unset($this->board);
    }

    /**
     * Checks whether the default values are applied when constructing a Field.
     *
     * @covers Simulator\Field::__construct()
     * @covers Simulator\Field::parentBoard()
     * @covers Simulator\Field::coordinate()
     * @covers Simulator\Field::value()
     */
    public function testCanBeConstructed()
    {
        $field = new Field(new Coordinate(1, 2), false, $this->board);

        $this->assertEquals($this->board, $field->parentBoard());
        $this->assertEquals(1, $field->coordinate()->x());
        $this->assertEquals(2, $field->coordinate()->y());
        $this->assertEquals(false, $field->value());
    }

    /**
     * Checks whether getters and setters work as expected.
     *
     * @dataProvider setAttributesProvider()
     * @covers Simulator\Field::setParentBoard()
     * @covers Simulator\Field::setCoordinate()
     * @covers Simulator\Field::setValue()
     * @covers Simulator\Field::parentBoard()
     * @covers Simulator\Field::coordinate()
     * @covers Simulator\Field::value()
     *
     * @param Board $_parentBoard The parent board
     * @param Coordinate $_coordinate The coordinate of the field
     * @param bool $_value Status of the field (true = alive, false = dead)
     */
    public function testCanSetAttributes(Board $_parentBoard, Coordinate $_coordinate, Bool $_value)
    {
        $field = new Field(new Coordinate(1, 2), false, $this->board);

        $field->setParentBoard($_parentBoard);
        $field->setCoordinate($_coordinate);
        $field->setValue($_value);

        $this->assertEquals($_parentBoard, $field->parentBoard());
        $this->assertEquals($_coordinate, $field->coordinate());
        $this->assertEquals($_value, $field->value());
    }

    /**
     * DataProvider for FieldTest::testCanSetAttributes.
     */
    public function setAttributesProvider()
    {
        $board = new Board(3, 4, false);

        return array(
            array($board, new Coordinate(1, 2), true),
            array($board, new Coordinate(2, 3), false),
            array($board, new Coordinate(4, 5), true)
        );
    }

    /**
     * Checks whether the Field correctly returns its status.
     *
     * @covers Simulator\Field::isAlive()
     * @covers Simulator\Field::isDead()
     */
    public function testCanReturnStatus()
    {
        $field = new Field(new Coordinate(0, 0), false, $this->board);

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
     * @covers Simulator\Field::numberOfDeadNeighbors()
     * @covers Simulator\Field::numberOfLivingNeighbors()
     * @dataProvider sumNeighborStatusesProvider
     *
     * @param int[] $_fieldCoordinates Coordinates of the field whose neighbours will be checked
     * @param int[][] $_livingNeighborsCoordinates Coordinates of the living neighbor cells
     * @param int $_amountLivingNeighbors Expected amount of living neighbors
     * @param int $_amountDeadNeighbors Expected amount of dead neighbors
     */
    public function testCanSumNeighborStatuses(array $_fieldCoordinates, array $_livingNeighborsCoordinates, int $_amountLivingNeighbors, int $_amountDeadNeighbors)
    {
        $field = new Field(new Coordinate($_fieldCoordinates["x"], $_fieldCoordinates["y"]), false, $this->board);

        foreach ($_livingNeighborsCoordinates as $livingNeighborCoordinates)
        {
            $this->board->setFieldState($livingNeighborCoordinates["x"], $livingNeighborCoordinates["y"], true);
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

    /**
     * Checks whether the number of border neighbors is correctly returned.
     *
     * @param int $_fieldX The X-Coordinate of the field that will be checked
     * @param int $_fieldY The Y-Coordinate of the field that will be checked
     * @param bool $_hasBorder Sets whether the test board has a border or not
     * @param int $_expectedResult The expected number of border neighbors
     *
     * @covers Simulator\Field::numberOfNeighborBorderFields()
     *
     * @dataProvider getNumberOfBorderNeighborsProvider
     */
    public function testCanGetNumberOfBorderNeighbors(int $_fieldX, int $_fieldY, bool $_hasBorder, int $_expectedResult)
    {
        $board = new Board(10, 10, $_hasBorder);

        /** @var Field $checkField */
        $checkField = $board->fields()[$_fieldY][$_fieldX];

        $this->assertEquals($_expectedResult, $checkField->numberOfNeighborBorderFields());
    }

    /**
     * DataProvider for FieldTest::testCanGetNumberOfBorderNeighbors.
     *
     * @return array Test values in the format array(fieldX, fieldY, hasBorder, expectedResult)
     */
    public function getNumberOfBorderNeighborsProvider()
    {
        return array(
            "with border, cell in center" => array(5, 5, true, 0),
            "with border, cell at left center border" => array(0, 5, true, 3),
            "with border, cell at right center border" => array(9, 5, true, 3),
            "with border, cell at top center border" => array(5, 0, true, 3),
            "with border, cell at bottom center border" => array(5, 9, true, 3),
            "with border, cell in top left corner" => array(0, 0, true, 5),
            "with border, cell in top right corner" => array(9, 0, true, 5),
            "with border, cell in bottom right corner" => array(9, 9, true, 5),
            "with border, cell in bottom left corner" => array(0, 9, true, 5),

            "without border, cell in center" => array(5, 5, false, 0),
            "without border, cell at left center border" => array(0, 5, false, 0),
            "without border, cell at right center border" => array(9, 5, false, 0),
            "without border, cell at top center border" => array(5, 0, false, 0),
            "without border, cell at bottom center border" => array(5, 9, false, 0),
            "without border, cell in top left corner" => array(0, 0, false, 0),
            "without border, cell in top right corner" => array(9, 0, false, 0),
            "without border, cell in bottom right corner" => array(9, 9, false, 0),
            "without border, cell in bottom left corner" => array(0, 9, false, 0),
        );
    }
}
