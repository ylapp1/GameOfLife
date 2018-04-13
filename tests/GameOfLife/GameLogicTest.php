<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use Simulator\GameLogic;
use Rule\ConwayRule;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Simulator\GameLogic works as expected.
 */
class GameLogicTest extends TestCase
{
    /**
     * Checks whether the calculateNextBoard() function works as expected.
     *
     * @covers \Simulator\GameLogic::calculateNextBoard()
     */
    public function testCanCalculateNextBoard()
    {
        $board = new Board(3, 3, 5, true);
        $gameLogic = new GameLogic(new ConwayRule());

        $board->setField(1, 0, true);
        $board->setField(1, 1, true);
        $board->setField(1, 2, true);
        $this->assertEquals(3, $board->getAmountCellsAlive());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($board->getFieldStatus(0, 1));
        $this->assertTrue($board->getFieldStatus(1, 1));
        $this->assertTrue($board->getFieldStatus(2, 1));
        $this->assertEquals(3, $board->getAmountCellsAlive());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($board->getFieldStatus(1, 0));
        $this->assertTrue($board->getFieldStatus(1, 1));
        $this->assertTrue($board->getFieldStatus(1, 2));
        $this->assertEquals(3, $board->getAmountCellsAlive());
    }

    /**
     * Checks whether border passthrough and solid work as expected.
     *
     * Places a 3 x 1 Blinker next to the right border of the board and checks the game step calculation results
     *
     * @covers \Simulator\GameLogic::calculateNextBoard()
     *
     */
    public function testCanChangeBorderType()
    {
        $gameLogic = new GameLogic(new ConwayRule());
        $board = new Board(10, 10, 50, true);

        // solid border
        $board->setField(9, 4, true);
        $board->setField(9, 5, true);
        $board->setField(9, 6, true);
        $this->assertEquals(3, $board->getAmountCellsAlive());
        $gameLogic->calculateNextBoard($board);

        $this->assertEquals(2, $board->getAmountCellsAlive());
        $this->assertTrue($board->getFieldStatus(9, 5));
        $this->assertTrue($board->getFieldStatus(8, 5));

        $gameLogic->calculateNextBoard($board);
        $this->assertEquals(0, $board->getAmountCellsAlive());


        // passthrough border
        $board->resetBoard();
        $board->setHasBorder(false);

        $board->setField(9, 4, true);
        $board->setField(9, 5, true);
        $board->setField(9, 6, true);
        $this->assertEquals(3, $board->getAmountCellsAlive());
        $gameLogic->calculateNextBoard($board);

        $this->assertEquals(3, $board->getAmountCellsAlive());
        $this->assertTrue($board->getFieldStatus(0, 5));
        $this->assertTrue($board->getFieldStatus(9, 5));
        $this->assertTrue($board->getFieldStatus(8, 5));

        $gameLogic->calculateNextBoard($board);
        $this->assertEquals(3, $board->getAmountCellsAlive());
        $this->assertTrue($board->getFieldStatus(9, 4));
        $this->assertTrue($board->getFieldStatus(9, 5));
        $this->assertTrue($board->getFieldStatus(9, 6));
    }

    /**
     * Checks whether loops are successfully detected.
     *
     * @covers \Simulator\GameLogic::calculateNextBoard()
     * @covers \Simulator\GameLogic::isLoopDetected()
     */
    public function testCanDetectLoops()
    {
        $board = new Board(3, 3, 5, true);

        // Place a 2x2 square on the field (static tile with conway rules)
        $board->setField(1, 1, true);
        $board->setField(1, 2, true);
        $board->setField(2, 1, true);
        $board->setField(2, 2, true);

        $gameLogic = new GameLogic(new ConwayRule());
        $this->assertFalse($gameLogic->isLoopDetected($board));

        $gameLogic->calculateNextBoard($board);
        $this->assertTrue($gameLogic->isLoopDetected($board));


        // Place a 1x3 blinker on the field (blinking tile with conway rules))
        $board->resetBoard();
        $board->setField(1,0, true);
        $board->setField(1, 1, true);
        $board->setField(1, 2, true);

        $gameLogic = new GameLogic(new ConwayRule());

        $this->assertFalse($gameLogic->isLoopDetected($board));

        $gameLogic->calculateNextBoard($board);
        $this->assertFalse($gameLogic->isLoopDetected($board));

        $gameLogic->calculateNextBoard($board);
        $this->assertTrue($gameLogic->isLoopDetected($board));
    }

    /**
     * Checks whether max steps reached can be successfully detected.
     *
     * @covers \Simulator\GameLogic::isMaxStepsReached()
     */
    public function testCanDetectMaxStepsReached()
    {
        $board = new Board(1, 1, 3, true);
        $gameLogic = new GameLogic(new ConwayRule());

        // Less than max steps
        $board->setGameStep(1);
        $this->assertFalse($gameLogic->isMaxStepsReached($board));

        // Equal to max steps
        $board->setGameStep(2);
        $this->assertTrue($gameLogic->isMaxStepsReached($board));

        // Greater than max steps
        $board->setGameStep(14);
        $this->assertTrue($gameLogic->isMaxStepsReached($board));
    }

    /**
     * Checks whether an empty board can be detected by the game logic.
     *
     * @covers \Simulator\GameLogic::isBoardEmpty()
     */
    public function testCanDetectEmptyBoard()
    {
        $board = new Board(1, 1, 2, true);
        $gameLogic = new GameLogic(new ConwayRule());

        $this->assertTrue($gameLogic->isBoardEmpty($board));

        $board->setField(0, 0, true);
        $this->assertFalse($gameLogic->isBoardEmpty($board));
    }
}
