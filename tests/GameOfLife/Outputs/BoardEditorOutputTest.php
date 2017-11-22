<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use Output\BoardEditorOutput;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the BoardEditor output works as expected.
 */
class BoardEditorOutputTest extends TestCase
{
    /**
     * Checks whether the output looks like expected.
     *
     * @covers \Output\BoardEditorOutput::outputBoard()
     * @covers \Output\BoardEditorOutput::getBoardContentString()
     * @covers \Output\BoardEditorOutput::getRowOutputString()
     */
    public function testCanOutputBoard()
    {
        $testBoard = new Board(5, 5, 3, 1);
        $testBoard->setField(1, 2, true);
        $testBoard->setField(2, 1, true);
        $testBoard->setField(2, 2, true);
        $testBoard->setField(2, 3, true);
        $testBoard->setField(3, 2, true);

        $output = new BoardEditorOutput;

        // Without highlighting
        $expectedOutput = "╔═════╗\n"
                        . "║     ║\n"
                        . "║  o  ║\n"
                        . "║ ooo ║\n"
                        . "║  o  ║\n"
                        . "║     ║\n"
                        . "╚═════╝\n";

        $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");
        $output->outputBoard($testBoard);

        // With x/y highlighting
        $expectedOutput = "    2\n"
                        . "╔═══════╗\n"
                        . "║  ║ ║  ║\n"
                        . "║  ║o║  ║\n"
                        . "║ o║o║o ║\n"
                        . "║═══════║\n"
                        . "║  ║X║  ║ 3\n"
                        . "║═══════║\n"
                        . "║  ║ ║  ║\n"
                        . "╚═══════╝\n";

        $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");
        $output->outputBoard($testBoard, 2, 3);
    }
}