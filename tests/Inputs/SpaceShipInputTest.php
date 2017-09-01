<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\SpaceShipInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;

/**
 * Class SpaceShipInputTest
 */
class SpaceShipInputTest extends TestCase
{
    /** @var SpaceShipInput $input */
    private $input;

    protected function setUp()
    {
        $this->input = new SpaceShipInput();
    }

    protected function tearDown()
    {
        unset($this->input);
    }

    public function testCanAddOptions()
    {
        $options = new Getopt();
        $this->input->addOptions($options);
        $optionList = $options->getOptionList();

        $this->assertEquals(2, count($optionList));
        $this->assertContains("spaceShipPosX", $optionList[0]);
        $this->assertContains("spaceShipPosY", $optionList[1]);
    }

    /**
     * @dataProvider setCellsProvider
     *
     * @param int $x            X-Coordinate of the cell
     * @param int $y            Y-Coordinate of the cell
     * @param bool $expected    Expected value of the cell
     */
    public function testCanSetCells($x, $y, $expected)
    {
        $options = new Getopt();
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $board = new Board(10, 10, 50, true, $rules);

        $this->input->fillBoard($board, $options);

        $this->assertEquals(9, $board->getAmountCellsAlive());
        $this->assertEquals($expected, $board->getField($x, $y));
    }

    public function setCellsProvider()
    {
        return [
            "Cell 5|4" => [5, 4, true],
            "Cell 6|4" => [6, 4, true],
            "Cell 7|4" => [7, 4, true],
            "Cell 8|4" => [8, 4, true],
            "Cell 4|5" => [4, 5, true],
            "Cell 8|5" => [8, 5, true],
            "Cell 8|6" => [8, 6, true],
            "Cell 4|7" => [4, 7, true],
            "Cell 7|7" => [7, 7, true]
        ];
    }
}