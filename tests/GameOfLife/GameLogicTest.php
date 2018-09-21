<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Simulator\Board;
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
        $board = new Board(3, 3, true);
        $gameLogic = new GameLogic(new ConwayRule(), 50);

        $board->setFieldState(1, 0, true);
        $board->setFieldState(1, 1, true);
        $board->setFieldState(1, 2, true);
        $this->assertEquals(3, $board->getNumberOfLivingCells());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($board->getFieldState(0, 1));
        $this->assertTrue($board->getFieldState(1, 1));
        $this->assertTrue($board->getFieldState(2, 1));
        $this->assertEquals(3, $board->getNumberOfLivingCells());

        $gameLogic->calculateNextBoard($board);

        $this->assertTrue($board->getFieldState(1, 0));
        $this->assertTrue($board->getFieldState(1, 1));
        $this->assertTrue($board->getFieldState(1, 2));
        $this->assertEquals(3, $board->getNumberOfLivingCells());
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
        $gameLogic = new GameLogic(new ConwayRule(), 50);
        $board = new Board(10, 10, true);

        // solid border
        $board->setFieldState(9, 4, true);
        $board->setFieldState(9, 5, true);
        $board->setFieldState(9, 6, true);
        $this->assertEquals(3, $board->getNumberOfLivingCells());
        $gameLogic->calculateNextBoard($board);

        $this->assertEquals(2, $board->getNumberOfLivingCells());
        $this->assertTrue($board->getFieldState(9, 5));
        $this->assertTrue($board->getFieldState(8, 5));

        $gameLogic->calculateNextBoard($board);
        $this->assertEquals(0, $board->getNumberOfLivingCells());


        // passthrough border
        $board->resetFields();
        $board->setHasBorder(false);

        $board->setFieldState(9, 4, true);
        $board->setFieldState(9, 5, true);
        $board->setFieldState(9, 6, true);
        $this->assertEquals(3, $board->getNumberOfLivingCells());
        $gameLogic->calculateNextBoard($board);

        $this->assertEquals(3, $board->getNumberOfLivingCells());
        $this->assertTrue($board->getFieldState(0, 5));
        $this->assertTrue($board->getFieldState(9, 5));
        $this->assertTrue($board->getFieldState(8, 5));

        $gameLogic->calculateNextBoard($board);
        $this->assertEquals(3, $board->getNumberOfLivingCells());
        $this->assertTrue($board->getFieldState(9, 4));
        $this->assertTrue($board->getFieldState(9, 5));
        $this->assertTrue($board->getFieldState(9, 6));
    }

    /**
     * Checks whether loops are successfully detected.
     *
     * @covers \Simulator\GameLogic::calculateNextBoard()
     * @covers \Simulator\GameLogic::isLoopDetected()
     */
    public function testCanDetectLoops()
    {
        $board = new Board(3, 3, true);

        // Place a 2x2 square on the field (static tile with conway rules)
        $board->setFieldState(1, 1, true);
        $board->setFieldState(1, 2, true);
        $board->setFieldState(2, 1, true);
        $board->setFieldState(2, 2, true);

        $gameLogic = new GameLogic(new ConwayRule(), 5);
        $this->assertFalse($gameLogic->isLoopDetected($board));

        $gameLogic->calculateNextBoard($board);
        $this->assertTrue($gameLogic->isLoopDetected($board));


        // Place a 1x3 blinker on the field (blinking tile with conway rules))
        $board->resetFields();
        $board->setFieldState(1,0, true);
        $board->setFieldState(1, 1, true);
        $board->setFieldState(1, 2, true);

        $gameLogic = new GameLogic(new ConwayRule(), 5);

        $this->assertFalse($gameLogic->isLoopDetected($board));

        $gameLogic->calculateNextBoard($board);
        $this->assertFalse($gameLogic->isLoopDetected($board));

        $gameLogic->calculateNextBoard($board);
        $this->assertTrue($gameLogic->isLoopDetected($board));
    }

    /**
     * Checks whether max steps reached can be successfully detected.
     *
     * @covers \Simulator\GameLogic::isMaxStepReached()
     */
    public function testCanDetectMaxStepsReached()
    {
        $gameLogic = new GameLogic(new ConwayRule(), 3);

        // Less than max steps
        $gameLogic->setGameStep(1);
        $this->assertFalse($gameLogic->isMaxStepReached());

        // Equal to max steps
        $gameLogic->setGameStep(3);
        $this->assertTrue($gameLogic->isMaxStepReached());

        // Greater than max steps
        $gameLogic->setGameStep(14);
        $this->assertTrue($gameLogic->isMaxStepReached());
    }

    /**
     * Checks whether an empty board can be detected by the game logic.
     *
     * @covers \Simulator\GameLogic::isBoardEmpty()
     */
    public function testCanDetectEmptyBoard()
    {
        $board = new Board(1, 1, true);
        $gameLogic = new GameLogic(new ConwayRule(), 2);

        $this->assertTrue($gameLogic->isBoardEmpty($board));

        $board->setFieldState(0, 0, true);
        $this->assertFalse($gameLogic->isBoardEmpty($board));
    }
}
