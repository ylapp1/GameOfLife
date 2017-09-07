<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\RandomInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;

/**
 * Class BlinkerInputTest
 */
class RandomInputTest extends TestCase
{
    /** @var  RandomInput $input */
    private $input;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    protected function setUp()
    {
        $this->input = new RandomInput();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
            ->getMock();
    }

    protected function tearDown()
    {
        unset($this->input);
        unset($this->board);
        unset($this->optionsMock);
    }


    /**
     * @covers \Input\RandomInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $blinkerOptions = array(
            array(null, "fillPercent", Getopt::REQUIRED_ARGUMENT, "Percentage of living cells on a random board")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($blinkerOptions);

        $this->input->addOptions($this->optionsMock);
    }

    /**
     * @covers \Input\RandomInput::fillBoard()
     */
    public function testCanFillBoardRandomPercentage()
    {
        $minAmountCellsAlive = (float)$this->board->getAmountCellsAlive() * 0.15;
        $this->input->fillBoard($this->board, new Getopt());

        $amountCellsAlive = $this->board->getAmountCellsAlive();
        $this->assertGreaterThan($minAmountCellsAlive, $amountCellsAlive);
    }

    /**
     * @dataProvider fillBoardCustomPercentageProvider
     * @covers \Input\RandomInput::fillBoard()
     *
     * @param int $_fillPercentage  Fill Percentage
     * @param bool $_expectsError   Expects error message
     */
    public function testCanFillBoardCustomPercentage(int $_fillPercentage, bool $_expectsError)
    {
        $this->optionsMock->expects($this->exactly(1))
                          ->method("getOption")
                          ->with("fillPercent")
                          ->willReturn($_fillPercentage);

        if ($_expectsError) $this->expectOutputString("Error: There can't be more living cells than 100% of the fields.\n");
        $this->input->fillBoard($this->board, $this->optionsMock);

        if (! $_expectsError)
        {
            $expectedAmountCellsAlive = ceil($this->board->width() * $this->board->height() * $_fillPercentage / 100);
            $this->assertEquals($expectedAmountCellsAlive, $this->board->getAmountCellsAlive());
        }
    }

    public function fillBoardCustomPercentageProvider()
    {
        return [
            "0% filled" => [0, false],
            "20% filled" => [20, false],
            "50% filled" => [50, false],
            "76% filled" => [76, false],
            "90% filled" => [90, false],
            "99% filled" => [99, false],
            "100% filled" => [100, false],
            "101% filled" => [101, true],
            "130% filled" => [130, true],
            "2500% filled" => [2500, true]
        ];
    }
}