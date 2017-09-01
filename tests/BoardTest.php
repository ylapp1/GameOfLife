<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;

use GameOfLife\RuleSet;
use GameOfLife\Board;
use Input\BlinkerInput;
use Ulrichsg\Getopt;

/**
 * Class BoardTest
 */
class BoardTest extends TestCase
{
    /** @var Board $board */
    private $board;

    // Called before and after each test
    protected function setUp()
    {
        $rulesComway = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rulesComway);
    }

    protected function tearDown()
    {
        unset($this->board);
    }


    /**
     * @dataProvider setFieldsProvider
     *
     * @param int $_x    X-Coordinate of test field
     * @param int $_y    Y-Coordinate of test field
     * @param bool $_value  Value that the test field will be set to
     * @param bool $_expected  Expected value that is stored in the array $currentBoard
     */
    public function testCanSetField($_x, $_y, $_value, $_expected)
    {
        $this->board->setField($_x, $_y, $_value);

        $this->assertEquals($_expected, @$this->board->currentBoard()[$_y][$_x]);
    }

    public function setFieldsProvider()
    {
        return [
            "Setting a cell to true" => [0, 0, true, true],
            "Setting a cell to false" => [0, 0, false, null],
        ];
    }


    /**
     * @dataProvider readFieldsProvider
     *
     * @param int $_x    X-Coordinate of test field
     * @param int $_y    Y-Coordinate of test field
     * @param bool $_value  Value that the test field will be set to
     * @param bool $_expected  Expected value that is read with getField()
     */
    public function testCanReadField($_x, $_y, $_value, $_expected)
    {
        $testBoard = $this->board->initializeEmptyBoard();
        $testBoard[$_y][$_x] = $_value;

        $this->board->setCurrentBoard($testBoard);

        $this->assertEquals($_expected, $this->board->getField($_x, $_y));
    }

    public function readFieldsProvider()
    {
        return [
            "Reading value true" => [0, 0, true, true],
            "Reading value false" => [0, 0, null, false]
        ];
    }


    public function testCanInitializeEmptyBoard()
    {
        $emptyBoard = $this->board->initializeEmptyBoard();

        $amountCellsAlive = 0;

        foreach ($emptyBoard as $line)
        {
            foreach ($line as $cell)
            {
                if (isset($cell)) $amountCellsAlive++;
            }
        }

        $this->assertEquals($amountCellsAlive, 0);
    }


    public function testCanAddToHistoryOfBoards()
    {
        $emptyBoard = $this->board->initializeEmptyBoard();

        // Check whether initial amount of boards in history is 0
        $this->assertEquals(0, count($this->board->historyOfBoards()));

        // Check whether using addToHistoryOfBoards 5 times will result in the history of boards storing 5 boards
        for ($i = 0; $i < 5; $i++)
        {
            $this->board->addToHistoryOfBoards($emptyBoard);
        }
        $this->assertEquals(5, count($this->board->historyOfBoards()));

        // Check whether using addToHistoryOfBoards more than 15 times in total will result in the history of boards storing exactly 15 boards
        for ($i = 0; $i < 12; $i++)
        {
            $this->board->addToHistoryOfBoards($emptyBoard);
        }
        $this->assertEquals(15, count($this->board->historyOfBoards()));
    }


    /**
     * @dataProvider amountCellsAliveProvider
     *
     * @param array(array) $_cells      Coordinates of living cells ([[x, y], [x, y], ...])
     * @param int $_expected            Amount of set cells that are expected
     */
    public function testCanCalculateAmountCellsAlive($_cells, $_expected)
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


    public function testDetectsFinish()
    {
        // check whether empty board is detected
        $this->assertEquals(true, $this->board->isFinished());

        // check whether living cells are detected
        $this->board->setField(0, 0, true);
        $this->assertEquals(false, $this->board->isFinished());

        // check whether max step makes the board stop
        $this->board->setGameStep(50);
        $this->assertEquals(true, $this->board->isFinished());

        // Check whether repeating pattern is detected
        $input = new BlinkerInput();
        $options = new Getopt();
        $input->fillBoard($this->board, $options);

        $this->board->calculateStep();
        $this->board->calculateStep();

        $this->assertEquals(true, $this->board->isFinished());
    }

    /**
     * @dataProvider amountNeighboursAliveProvider
     *
     * @param array(array) $_cells       Coordinates of living cells ([[x, y], [x, y], ...])
     * @param int $_x                    X-Coordinate of inspected cell
     * @param int $_y                    Y-Coordinate of inspected cell
     * @param int $_expected             Expected amount of neighbours
     */
    public function testCanCalculateAmountNeighboursAlive($_cells, $_x, $_y, $_expected)
    {
        foreach ($_cells as $cell)
        {
            $this->board->setField($cell[0], $cell[1], true);
        }

        $this->assertEquals($_expected, $this->board->calculateAmountNeighboursAlive($_x, $_y));
    }

    public function amountNeighboursAliveProvider()
    {
        return [
            "Three Cells Set, One Neighbour" => [[[0, 1], [0, 2], [0, 3]] , 0, 0, 1],
            "Three Cells Set, Two Neighbours" => [[[1, 1], [1, 3], [1, 4]], 1, 2, 2],
            "Three Cells Set, Three Neighbours" => [[[2, 1], [2, 3], [3, 2]], 2, 2, 3]
        ];
    }


    /**
     * @dataProvider calculateNewCellStateProvider
     *
     * @param bool $_currentCellState       Current cell state (dead = false; alive = true)
     * @param int $_amountNeighboursAlive   Amount of living neighbours (0 - 8)
     * @param bool $_expected               Expected new cell state
     */
    public function testCanCalculateNewCellState($_currentCellState, $_amountNeighboursAlive, $_expected)
    {
        $this->assertEquals($_expected, $this->board->calculateNewCellState($_currentCellState, $_amountNeighboursAlive));
    }

    public function calculateNewCellStateProvider()
    {
        return [
            "Dead Cell, One Neighbour" => [false, 1, false],
            "Dead Cell, Three Neighbours" => [false, 3, true],
            "Dead Cell, Six Neighbours" => [false, 6, false],

            "Living Cell, Zero Neighbours" => [true, 0, false],
            "Living Cell, Two Neighbours" => [true, 2, true],
            "Living Cell, Three Neighbours" => [true, 3, true],
            "Living Cell, Eight Neighbours" => [true, 8, false]
        ];
    }


    /**
     * @dataProvider calculateCenterProvider
     *
     * @param int $_boardWidth      Board width
     * @param int $_boardHeight     Board height
     * @param array $expected       Coordinates of the center
     */
    public function testCanCalculateCenter($_boardWidth, $_boardHeight, $expected)
    {
        $this->board->setWidth($_boardWidth);
        $this->board->setHeight($_boardHeight);

        $this->assertEquals($expected, $this->board->getCenter());
    }

    public function calculateCenterProvider()
    {
        return [
            "10x15 Board, Center = 4|7" => [10, 15, ["x" => 4, "y" => 7]],
            "23x48 Board, Center = 11|23" => [23, 48, ["x" => 11, "y" => 23]],
            "1x7 Board, Center 0|3" => [1, 7, ["x" => 0, "y" => 3]]
        ];
    }


    public function testCanBeConvertedToString()
    {
        $this->assertNotEmpty(@strval($this->board));
    }
}