<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\GameLogic;
use Rule\ComwayRule;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \GameOfLife\GameLogic works as expected.
 */
class GameLogicTest extends TestCase
{
    /**
     * Checks whether the constructor sets the attributes as expected.
     *
     * @covers \GameOfLife\GameLogic::__construct()
     * @covers \GameOfLife\GameLogic::rule()
     */
    public function testCanBeConstructed()
    {
        $rule = new ComwayRule();
        $gameLogic = new GameLogic($rule);

        $this->assertEquals($rule, $gameLogic->rule());
    }

    /**
     * Checks whether getters and setters work as expected.
     *
     * @covers \GameOfLife\GameLogic::setCurrentBoard()
     * @covers \GameOfLife\GameLogic::setHistoryOfBoards()
     * @covers \GameOfLife\GameLogic::currentBoard()
     * @covers \GameOfLife\GameLogic::historyOfBoards()
     * @covers \GameOfLife\GameLogic::setRule()
     * @covers \GameOfLife\GameLogic::rule()
     */
    public function testCanSetAttributes()
    {
        $gameLogic = new GameLogic(new ComwayRule());

        $board = new Board(5, 5, 1, true);
        $fields = (string)$board;
        $historyTest = array($fields, $fields, $fields, $fields);

        $gameLogic->setCurrentBoard($fields);
        $gameLogic->setHistoryOfBoards($historyTest);
        $gameLogic->setRule(new ComwayRule());

        $this->assertEquals($fields, $gameLogic->currentBoard());
        $this->assertEquals($historyTest, $gameLogic->historyOfBoards());
        $this->assertEquals(new ComwayRule(), $gameLogic->rule());
    }

    /**
     * Checks whether the calculateNextBoard() function works as expected.
     *
     * @covers \GameOfLife\GameLogic::addToHistory()
     * @covers \GameOfLife\GameLogic::calculateNextBoard()
     */
    public function testCanCalculateNextBoard()
    {
        $board = new Board(3, 3, 5, true);
        $gameLogic = new GameLogic(new ComwayRule());

        $board->setField(1, 0, true);
        $board->setField(1, 1, true);
        $board->setField(1, 2, true);
        $this->assertEquals(3, $board->getAmountCellsAlive());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($board->getField(0, 1));
        $this->assertTrue($board->getField(1, 1));
        $this->assertTrue($board->getField(2, 1));
        $this->assertEquals(3, $board->getAmountCellsAlive());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($board->getField(1, 0));
        $this->assertTrue($board->getField(1, 1));
        $this->assertTrue($board->getField(1, 2));
        $this->assertEquals(3, $board->getAmountCellsAlive());

        $this->assertEquals(2, count($gameLogic->historyOfBoards()));
    }

    /**
     * Checks whether border passthrough and solid work as expected.
     *
     * Places a 3 x 1 Blinker next to the right border of the board and checks the game step calculation results
     *
     * @covers \GameOfLife\GameLogic::calculateNextBoard()
     *
     */
    public function testCanChangeBorderType()
    {
        $gameLogic = new GameLogic(new ComwayRule());
        $board = new Board(10, 10, 50, true);

        // solid border
        $board->setField(9, 4, true);
        $board->setField(9, 5, true);
        $board->setField(9, 6, true);
        $this->assertEquals(3, $board->getAmountCellsAlive());
        $gameLogic->calculateNextBoard($board);

        $this->assertEquals(2, $board->getAmountCellsAlive());
        $this->assertTrue($board->getField(9, 5));
        $this->assertTrue($board->getField(8, 5));

        $gameLogic->calculateNextBoard($board);
        $this->assertEquals(0, $board->getAmountCellsAlive());


        // passthrough border
        $board->resetCurrentBoard();
        $board->setHasBorder(false);

        $board->setField(9, 4, true);
        $board->setField(9, 5, true);
        $board->setField(9, 6, true);
        $this->assertEquals(3, $board->getAmountCellsAlive());
        $gameLogic->calculateNextBoard($board);

        $this->assertEquals(3, $board->getAmountCellsAlive());
        $this->assertTrue($board->getField(0, 5));
        $this->assertTrue($board->getField(9, 5));
        $this->assertTrue($board->getField(8, 5));

        $gameLogic->calculateNextBoard($board);
        $this->assertEquals(3, $board->getAmountCellsAlive());
        $this->assertTrue($board->getField(9, 4));
        $this->assertTrue($board->getField(9, 5));
        $this->assertTrue($board->getField(9, 6));
    }

    /**
     * Checks whether loops are successfully detected.
     *
     * @covers \GameOfLife\GameLogic::addToHistory()
     * @covers \GameOfLife\GameLogic::calculateNextBoard()
     * @covers \GameOfLife\GameLogic::isLoopDetected()
     */
    public function testCanDetectLoops()
    {
        $board = new Board(3, 3, 5, true);

        // Place a 2x2 square on the field (static tile with comway rules)
        $board->setField(1, 1, true);
        $board->setField(1, 2, true);
        $board->setField(2, 1, true);
        $board->setField(2, 2, true);

        $gameLogic = new GameLogic(new ComwayRule());
        $this->assertFalse($gameLogic->isLoopDetected());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($gameLogic->isLoopDetected());
        $this->assertEquals(1, count($gameLogic->historyOfBoards()));
    }
}