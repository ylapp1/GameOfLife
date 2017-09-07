<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\FileInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;

/**
 * Class FileInputTest
 */
class FileInputTest extends TestCase
{
    /** @var  FileInput $input */
    private $input;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    protected function setUp()
    {
        $this->input = new FileInput();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();
    }

    protected function tearDown()
    {
        unset($this->input);
        unset($this->optionsMock);
    }


    /**
     * @dataProvider setAttributesProvider
     * @covers \Input\FileInput::setNewBoardHeight()
     * @covers \Input\FileInput::newBoardHeight()
     * @covers \Input\FileInput::setNewBoardWidth()
     * @covers \Input\FileInput::newBoardWidth()
     *
     * @param int $_newBoardWidth   New width of board after reading template
     * @param int $_newBoardHeight  New height of board after reading template
     */
    public function testCanSetAttributes($_newBoardWidth, $_newBoardHeight)
    {
        $this->input->setNewBoardWidth($_newBoardWidth);
        $this->assertEquals($_newBoardWidth, $this->input->newBoardWidth());

        $this->input->setNewBoardHeight($_newBoardHeight);
        $this->assertEquals($_newBoardHeight, $this->input->newBoardHeight());
    }

    public function setAttributesProvider()
    {
        return [
            [1, 2],
            [23, 76],
            [45, 98],
            [33, 55]
        ];
    }


    /**
     * @covers \Input\FileInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $fileInputOptions = array(
            array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration"));

        $this->optionsMock->expects($this->exactly(1))
            ->method("addOptions")
            ->with($fileInputOptions);

        $this->input->addOptions($this->optionsMock);
    }

    /**
     * @covers \Input\FileInput::loadTemplate()
     */
    public function testCanLoadTemplate()
    {
        $board = $this->input->loadTemplate("unittest");

        $this->assertNotEmpty($board);
        $this->assertEquals(2, $this->input->newBoardWidth());
        $this->assertEquals(2, $this->input->newBoardHeight());

        $this->assertEquals(true, $board[0][0]);
        $this->assertEquals(null, @$board[0][1]);
        $this->assertEquals(null, @$board[1][0]);
        $this->assertEquals(null, @$board[1][1]);
    }

    /**
     * @dataProvider invalidTemplateNamesProvider
     * @covers \Input\FileInput::loadTemplate()
     *
     * @param string $_templateName
     */
    public function testDetectsInvalidTemplateNames(string $_templateName)
    {
        $this->input->loadTemplate($_templateName);
        $this->expectOutputString("Error: Template file not found!\n");
    }

    public function invalidTemplateNamesProvider()
    {
        return [
            ["test"],
            ["mytest"],
            ["notexisting"],
            ["mytemplate"],
            ["hello"]
        ];
    }


    /**
     * @covers \Input\FileInput::fillBoard
     */
    public function testCanFillBoard()
    {
        $this->optionsMock->method("getOption")
                          ->with("template")
                          ->willReturn("unittest");

        $unitTestBoard = array(
            array(0 => true),
            array()
        );

        $this->input->fillBoard($this->board, $this->optionsMock);

        $this->assertEquals(2, $this->board->width());
        $this->assertEquals(2, $this->board->height());
        $this->assertEquals($unitTestBoard, $this->board->currentBoard());
    }

    /**
     * @covers \Input\FileInput::fillBoard()
     */
    public function testDetectsEmptyTemplateName()
    {
        $this->expectOutputString("Error: No template file specified\n");
        $this->input->fillBoard($this->board, new Getopt());
    }
}
