<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\Field;
use GameOfLife\GameLogic;
use GameOfLife\RuleSet;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \GameOfLife\GameLogic works as expected.
 */
class GameLogicTest extends TestCase
{
    /**
     * Checks whether getters and setters work as expected.
     *
     * @covers \GameOfLife\GameLogic::setCurrentBoard()
     * @covers \GameOfLife\GameLogic::setHistoryOfBoards()
     * @covers \GameOfLife\GameLogic::currentBoard()
     * @covers \GameOfLife\GameLogic::historyOfBoards()
     */
    public function testCanSetAttributes()
    {
        $gameLogic = new GameLogic();

        $board = new Board(5, 5, 1, true, new RuleSet(array(), array()));
        $fields = $board->fields();
        $historyTest = array($fields, $fields, $fields, $fields);

        $gameLogic->setCurrentBoard($fields);
        $gameLogic->setHistoryOfBoards($historyTest);

        $this->assertEquals($fields, $gameLogic->currentBoard());
        $this->assertEquals($historyTest, $gameLogic->historyOfBoards());
    }

    /**
     * Checks whether the calculateNextBoard() function works as expected.
     *
     * @covers \GameOfLife\GameLogic::addToHistory()
     * @covers \GameOfLife\GameLogic::calculateNextBoard()
     */
    public function testCanCalculateNextBoard()
    {
        $board = new Board(2, 2, 5, true, new RuleSet(array(3), array(0, 1, 2, 3, 4, 5)));

        $board->setField(0, 0, true);
        $board->setField(0, 1, true);
        $board->setField(1, 0, true);

        $gameLogic = new GameLogic();
        $gameLogic->calculateNextBoard($board);

        $this->assertFalse($board->getField(0, 0));
        $this->assertFalse($board->getField(0, 1));
        $this->assertFalse($board->getField(1, 0));
        $this->assertTrue($board->getField(1, 1));

        $gameLogic->calculateNextBoard($board);

        $this->assertFalse($board->getField(0, 0));
        $this->assertFalse($board->getField(0, 1));
        $this->assertFalse($board->getField(1, 0));
        $this->assertFalse($board->getField(1, 1));

        $this->assertEquals(2, count($gameLogic->historyOfBoards()));
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
        $rulesComway = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $board = new Board(3, 3, 5, true, $rulesComway);

        // Place a 2x2 square on the field (static tile with comway rules)
        $board->setField(1, 1, true);
        $board->setField(1, 2, true);
        $board->setField(2, 1, true);
        $board->setField(2, 2, true);

        $gameLogic = new GameLogic();
        $this->assertFalse($gameLogic->isLoopDetected());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($gameLogic->isLoopDetected());
        $this->assertEquals(1, count($gameLogic->historyOfBoards()));
    }
}