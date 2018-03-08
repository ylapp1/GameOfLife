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
 * Checks whether \GameOfLife\Board works as expected.
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
        $this->board = new Board(20, 17, 250, false);
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
     * @param int $_maxSteps    Maximum amount of steps
     * @param bool $_hasBorder  Defines whether board has a border
     */
    public function testCanBeConstructed(int $_width, int $_height, int $_maxSteps, bool $_hasBorder)
    {
        $testBoard = new Board($_width, $_height, $_maxSteps, $_hasBorder);

        $this->assertEquals(0, $testBoard->gameStep());
        $this->assertEquals($_hasBorder, $testBoard->hasBorder());
        $this->assertEquals($_height, $testBoard->height());
        $this->assertEquals($_maxSteps, $testBoard->maxSteps());
        $this->assertEquals($_width, $testBoard->width());
        $this->assertEquals($testBoard->initializeEmptyBoard(), $testBoard->fields());
    }

    public function constructionProvider()
    {
        return [
            [0, 1, 200, true],
            [2, 3, 100, true],
            [4, 5, 57, false],
            [6, 7, 34, false]
        ];
    }

    /**
     * @covers \GameOfLife\Board::fields()
     * @covers \GameOfLife\Board::hasBorder()
     * @covers \GameOfLife\Board::height()
     * @covers \GameOfLife\Board::maxSteps()
     * @covers \GameOfLife\Board::width()
     * @covers \GameOfLife\Board::gameStep()
     */
    public function testCanGetAttributes()
    {
        $this->assertEquals(17, count($this->board->fields()));
        $this->assertFalse($this->board->hasBorder());
        $this->assertEquals(17, $this->board->height());
        $this->assertEquals(250, $this->board->maxSteps());
        $this->assertEquals(20, $this->board->width());
        $this->assertEquals(0, $this->board->gameStep());
    }


    /**
     * @dataProvider setAttributesProvider
     * @covers \GameOfLife\Board::setFields()
     * @covers \GameOfLife\Board::setHasBorder()
     * @covers \GameOfLife\Board::setHeight()
     * @covers \GameOfLife\Board::setMaxSteps()
     * @covers \GameOfLife\Board::setWidth()
     * @covers \GameOfLife\Board::setGameStep()
     *
     * @param array $_board             Current board
     * @param bool $_hasBorder          Border type
     * @param int $_height              Board height
     * @param int $_maxSteps            Maximum amount of steps that are calculated
     * @param int $_width               Board width
     * @param int $_gameStep            Current game step
     */
    public function testCanSetAttributes(array $_board, bool $_hasBorder, int $_height,
                                         int $_maxSteps, int $_width, int $_gameStep)
    {
        $this->board->setFields($_board);
        $this->board->setHasBorder($_hasBorder);
        $this->board->setHeight($_height);
        $this->board->setMaxSteps($_maxSteps);
        $this->board->setWidth($_width);
        $this->board->setGameStep($_gameStep);

        $this->assertEquals($_board, $this->board->fields());
        $this->assertEquals($_hasBorder, $this->board->hasBorder());
        $this->assertEquals($_height, $this->board->height());
        $this->assertEquals($_maxSteps, $this->board->maxSteps());
        $this->assertEquals($_width, $this->board->width());
        $this->assertEquals($_gameStep, $this->board->gameStep());
    }

    public function setAttributesProvider()
    {
        $emptyBoard = array(array());

        return [
            [$emptyBoard, true, 20, 10, 30, 200],
            [$emptyBoard, false, 67, 13, 45, 384],
            [$emptyBoard, true, 256, 124, 6, 1789]
        ];
    }

    /**
     * @dataProvider setFieldsProvider
     * @covers \GameOfLife\Board::setField()
     *
     * @param int $_x           X-Coordinate of test field
     * @param int $_y           Y-Coordinate of test field
     * @param bool $_value      Value that the test field will be set to
     * @param bool $_expected   Expected value that is stored in the array $currentBoard
     */
    public function testCanSetField(int $_x, int $_y, bool $_value = null, bool $_expected = null)
    {
        $this->board->setField($_x, $_y, $_value);

        $this->assertEquals($_expected, $this->board->getFieldStatus($_x, $_y));
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
     * @covers \GameOfLife\Board::getFieldStatus()
     *
     * @param int $_x           X-Coordinate of test field
     * @param int $_y           Y-Coordinate of test field
     * @param bool $_value      Value that the test field will be set to
     * @param bool $_expected   Expected value that is read with getField()
     */
    public function testCanReadField(int $_x, int $_y, bool $_value = null, bool $_expected)
    {
        $testBoard = $this->board->initializeEmptyBoard();
        $field = $testBoard[$_y][$_x];

        if ($field instanceof Field) $field->setValue($_value);

        $this->board->setFields($testBoard);

        $this->assertEquals($_expected, $this->board->getFieldStatus($_x, $_y));
    }

    public function readFieldsProvider()
    {
        return [
            "Reading value true" => [0, 0, true, true],
            "Reading value false" => [0, 0, false, false]
        ];
    }


    /**
     * @covers \GameOfLife\Board::initializeEmptyBoard()
     */
    public function testCanInitializeEmptyBoard()
    {
        $emptyBoard = $this->board->initializeEmptyBoard();

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
     * @covers \GameOfLife\Board::getAmountCellsAlive()
     *
     * @param array(array) $_cells      Coordinates of living cells ([[x, y], [x, y], ...])
     * @param int $_expected            Amount of set cells that are expected
     */
    public function testCanCalculateAmountCellsAlive(array $_cells, int $_expected)
    {
        foreach ($_cells as $cell)
        {
            $this->board->setField($cell[0], $cell[1], true);
        }

        $this->assertEquals($_expected, $this->board->getAmountCellsAlive());
    }

    public function amountCellsAliveProvider()
    {
        return [
            [[[0, 0], [0, 1]], 2],
            [[[0, 0]], 1],
            [[[1, 2,], [2, 4], [4, 5]], 3]
        ];
    }

    /**
     * @dataProvider calculateCenterProvider
     * @covers \GameOfLife\Board::getCenter()
     *
     * @param int $_boardWidth      Board width
     * @param int $_boardHeight     Board height
     * @param array $_expected      Coordinates of the center
     */
    public function testCanCalculateCenter(int $_boardWidth, int $_boardHeight, array $_expected)
    {
        $this->board->setWidth($_boardWidth);
        $this->board->setHeight($_boardHeight);

        $this->assertEquals($_expected, $this->board->getCenter());
    }

    public function calculateCenterProvider()
    {
        return [
            "10x15 Board, Center = 4|7" => [10, 15, ["x" => 4, "y" => 7]],
            "23x48 Board, Center = 11|23" => [23, 48, ["x" => 11, "y" => 23]],
            "1x7 Board, Center 0|3" => [1, 7, ["x" => 0, "y" => 3]]
        ];
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
            $this->board->setField($cell[0],$cell[1], true);
        }

        $this->assertEquals($_expected, $this->board->getFillPercentage());
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
     * @covers \GameOfLife\Board::resetBoard()
     */
    public function testCanResetCurrentBoard()
    {
        $this->board->setField(1, 1, true);
        $this->board->setField(0, 1, true);

        $this->assertEquals(2, $this->board->getAmountCellsAlive());

        $this->board->resetBoard();
        $this->assertEquals(0, $this->board->getAmountCellsAlive());
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
        $board = new Board(10, 10, 1, $_hasBorder);
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
                        $tmpField = new Field($board, $x, $y);

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
}
