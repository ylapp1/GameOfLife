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
 * Checks whether \Simulator\Board works as expected.
 *
 * @covers \GameOfLife\Board
 */
class BoardTest extends TestCase
{
    /** @var Board $board */
    private $board;

    // Called before and after each test
    protected function setUp()
    {
        $this->board = new Board(20, 1, false);
    }

    protected function tearDown()
    {
        unset($this->board);
    }


    /**
     * @dataProvider constructionProvider()
     * @covers \GameOfLife\Board::__construct()
     *
     * @param int $_width       Board width
     * @param int $_height      Board height
     * @param bool $_hasBorder  Defines whether board has a border
     */
    public function testCanBeConstructed(int $_width, int $_height, bool $_hasBorder)
    {
        $testBoard = new Board($_width, $_height, $_hasBorder);

        $this->assertEquals($_hasBorder, $testBoard->hasBorder());
        $this->assertEquals($_height, $testBoard->height());
        $this->assertEquals($_width, $testBoard->width());
        $this->assertEquals($testBoard->generateFieldsList(false), $testBoard->fields());
    }

    public function constructionProvider()
    {
        return [
            [0, 1, true],
            [2, 3, true],
            [4, 5, false],
            [6, 7, false]
        ];
    }

    /**
     * @covers \GameOfLife\Board::fields()
     * @covers \GameOfLife\Board::hasBorder()
     * @covers \GameOfLife\Board::height()
     * @covers \GameOfLife\Board::width()
     */
    public function testCanGetAttributes()
    {
        $this->assertEquals(17, count($this->board->fields()));
        $this->assertFalse($this->board->hasBorder());
        $this->assertEquals(17, $this->board->height());
        $this->assertEquals(20, $this->board->width());
    }


    /**
     * @dataProvider setAttributesProvider
     * @covers \GameOfLife\Board::setFields()
     * @covers \GameOfLife\Board::setHasBorder()
     * @covers \GameOfLife\Board::setHeight()
     * @covers \GameOfLife\Board::setWidth()
     *
     * @param array $_board             Current board
     * @param bool $_hasBorder          Border type
     * @param int $_height              Board height
     * @param int $_width               Board width
     */
    public function testCanSetAttributes(array $_board, bool $_hasBorder, int $_height, int $_width)
    {
        $this->board->setFields($_board);
        $this->board->setHasBorder($_hasBorder);
        $this->board->setHeight($_height);
        $this->board->setWidth($_width);

        $this->assertEquals($_board, $this->board->fields());
        $this->assertEquals($_hasBorder, $this->board->hasBorder());
        $this->assertEquals($_height, $this->board->height());
        $this->assertEquals($_width, $this->board->width());
    }

    public function setAttributesProvider()
    {
        $emptyBoard = array(array());

        return [
            [$emptyBoard, true, 20, 30],
            [$emptyBoard, false, 67, 45],
            [$emptyBoard, true, 256, 6]
        ];
    }

    /**
     * @dataProvider setFieldsProvider
     * @covers \GameOfLife\Board::setFieldState()
     *
     * @param int $_x           X-Coordinate of test field
     * @param int $_y           Y-Coordinate of test field
     * @param bool $_value      Value that the test field will be set to
     * @param bool $_expected   Expected value that is stored in the array $currentBoard
     */
    public function testCanSetField(int $_x, int $_y, bool $_value = null, bool $_expected = null)
    {
        $this->board->setFieldState($_x, $_y, $_value);

        $this->assertEquals($_expected, $this->board->getFieldState($_x, $_y));
    }

    public function setFieldsProvider()
    {
        return [
            "Setting a cell to true" => [0, 0, true, true],
            "Setting a cell to false" => [0, 0, false, false],
        ];
    }


    /**
     * @dataProvider readFieldsProvider
     * @covers \GameOfLife\Board::getFieldState()
     *
     * @param int $_x           X-Coordinate of test field
     * @param int $_y           Y-Coordinate of test field
     * @param bool $_value      Value that the test field will be set to
     * @param bool $_expected   Expected value that is read with getField()
     */
    public function testCanReadField(int $_x, int $_y, bool $_value = null, bool $_expected)
    {
        $testBoard = $this->board->generateFieldsList(false);
        $field = $testBoard[$_y][$_x];

        if ($field instanceof Field) $field->setValue($_value);

        $this->board->setFields($testBoard);

        $this->assertEquals($_expected, $this->board->getFieldState($_x, $_y));
    }

    public function readFieldsProvider()
    {
        return [
            "Reading value true" => [0, 0, true, true],
            "Reading value false" => [0, 0, false, false]
        ];
    }


    /**
     * @covers \GameOfLife\Board::generateFieldsList()
     */
    public function testCanInitializeEmptyBoard()
    {
        $emptyBoard = $this->board->generateFieldsList(false);

        $amountCellsAlive = 0;

        foreach ($emptyBoard as $line)
        {
            foreach ($line as $field)
            {
                if ($field instanceof Field)
                {
                    if ($field->isAlive()) $amountCellsAlive++;
                }
            }
        }

        $this->assertEquals($amountCellsAlive, 0);
    }


    /**
     * @dataProvider amountCellsAliveProvider
     * @covers \GameOfLife\Board::getNumberOfAliveFields()
     *
     * @param array(array) $_cells      Coordinates of living cells ([[x, y], [x, y], ...])
     * @param int $_expected            Amount of set cells that are expected
     */
    public function testCanCalculateAmountCellsAlive(array $_cells, int $_expected)
    {
        foreach ($_cells as $cell)
        {
            $this->board->setFieldState($cell[0], $cell[1], true);
        }

        $this->assertEquals($_expected, $this->board->getNumberOfAliveFields());
    }

    public function amountCellsAliveProvider()
    {
        return array(

            array(
                array(
                    array(0, 0),
                    array(0, 1)
                ),
                2
            ),
            array(
                array(
                    array(0, 0)
                ),
                1
            ),
            array(
                array(
                    array(1, 2),
                    array(2, 4),
                    array(4, 5)
                ),
                3
            )
        );
    }


    /**
     * @covers \GameOfLife\Board::__tostring()
     */
    public function testCanBeConvertedToString()
    {
        $this->assertNotEmpty(strval($this->board));
    }


    /**
     * @dataProvider calculateFillPercentProvider
     *
     * @param array $_cells     Cells that will be set to true
     * @param float $_expected  Expected fill percentage
     */
    public function testCanCalculateFillPercent(array $_cells, float $_expected)
    {
        $this->board->setWidth(5);
        $this->board->setHeight(10);

        foreach ($_cells as $cell)
        {
            $this->board->setFieldState($cell[0],$cell[1], true);
        }

        $this->assertEquals($_expected, $this->board->getPercentageOfAliveFields());
    }

    public function calculateFillPercentProvider()
    {
        return [
            "5 of 50 cells" => [[[0, 0], [0, 1], [0, 2], [0, 3], [0, 4]], 0.1],
            "7 of 50 cells" => [[[0, 0], [0, 1], [0, 2], [0, 3], [0, 4], [1, 0], [1, 1]], 0.14],
            "9 of 50 cells" => [[[0, 0], [0, 1], [0, 2], [0, 3], [0, 4], [1,0], [1, 1], [1, 2], [1, 3]], 0.18]
        ];
    }

    /**
     * @covers \GameOfLife\Board::resetFields()
     */
    public function testCanResetCurrentBoard()
    {
        $this->board->setFieldState(1, 1, true);
        $this->board->setFieldState(0, 1, true);

        $this->assertEquals(2, $this->board->getNumberOfAliveFields());

        $this->board->resetFields();
        $this->assertEquals(0, $this->board->getNumberOfAliveFields());
    }

    /**
     * Checks whether the correct neighbors of a field are returned for both border types (solid and passthrough).
     *
     * @dataProvider neighborsOfFieldProvider
     * @covers \GameOfLife\Board::getNeighborsOfField()
     *
     * @param int[string] $_targetField Coordinates of the target field in the format array("x" => x, "y" => y)
     * @param bool $_hasBorder Defines whether the board has a border
     * @param int[] $_neighborsX All possible X-Coordinates of neighbors
     * @param int[] $_neighborsY All possible Y-Coordinates of neighbors
     * @param int $_amountNeighbors Expected amount of neighbor cells
     */
    public function testCanGetNeighborsOfField($_targetField, $_hasBorder, $_neighborsX, $_neighborsY, $_amountNeighbors)
    {
        $board = new Board(10, 10, $_hasBorder);
        $field = $board->fields()[$_targetField["y"]][$_targetField["x"]];

        $neighbors = $board->getNeighborsOfField($field);

        $this->assertEquals($_amountNeighbors, count($neighbors));

        foreach ($_neighborsY as $y)
        {
            foreach ($_neighborsX as $x)
            {
                if ($field instanceof Field)
                {
                    if ($y != $field->y() || $x != $field->x())
                    {
                        $tmpField = new Field($x, $y, false, $board);

                        $this->assertNotFalse(array_search($tmpField, $neighbors));
                    }
                }
            }
        }
    }

    /**
     * DataProvider for BoardTest::testCanGetNeighborsOfField().
     *
     * @return array Test values
     */
    public function neighborsOfFieldProvider()
    {
        return array(
            "(1|1) Solid Border" => array(
                array("x" => 1, "y" => 1),
                true,
                array(0, 1, 2),
                array(0, 1, 2),
                8
            ),

            "(0|0) Solid Border" => array(
                array("x" => 0, "y" => 0),
                true,
                array(0, 1),
                array(0, 1),
                3
            ),

            "(0|1) Passthrough Border" => array(
                array("x" => 0, "y" => 1),
                false,
                array(9, 0, 1),
                array(0, 1, 2),
                8
            ),

            "(1|0) Passthrough Border" => array(
                array("x" => 1, "y" => 0),
                false,
                array(0, 1, 2),
                array(9, 0, 1),
                8
            ),

            "(9|9) Passthrough Border" => array(
                array("x" => 9, "y" => 9),
                false,
                array(8, 9, 0),
                array(8, 9, 0),
                8
            ),

        );
    }

    /**
     * Checks whether the board can be inverted.
     *
     * @covers \GameOfLife\Board::invertFields()
     */
    public function testCanInvertBoard()
    {
        $board = new Board(2, 2, true);
        $board->setFieldState(1, 1, true);

        $fieldZeroZero = new Field(0, 0, true, $board);
        $fieldOneZero = new Field(1, 0, true, $board);
        $fieldZeroOne = new Field(0, 1, true, $board);
        $fieldOneOne = new Field(1, 1, false, $board);

        $expectedFields = array(
            array($fieldZeroZero, $fieldOneZero),
            array($fieldZeroOne, $fieldOneOne)
        );

        $board->invertFields();

        $this->assertEquals($expectedFields, $board->fields());
    }
}
