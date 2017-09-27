<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Input\FileInput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\FileInput works as expected
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
        unset($this->board);
        unset($this->optionsMock);
    }

    /**
     * @dataProvider setAttributesProvider
     * @covers \Input\FileInput::setTemplateDirectory()
     * @covers \Input\FileInput::templateDirectory()
     * @covers \Input\FileInput::setTemplateHeight()
     * @covers \Input\FileInput::templateHeight()
     * @covers \Input\FileInput::setTemplateWidth()
     * @covers \Input\FileInput::templateWidth()
     *
     * @param string $_templateDirectory    Template directory
     * @param int $_templateWidth   New width of board after reading template
     * @param int $_templateHeight  New height of board after reading template
     */
    public function testCanSetAttributes($_templateDirectory, $_templateWidth, $_templateHeight)
    {
        $this->input->setTemplateDirectory($_templateDirectory);
        $this->assertEquals($_templateDirectory, $this->input->templateDirectory());

        $this->input->setTemplateHeight($_templateHeight);
        $this->assertEquals($_templateHeight, $this->input->templateHeight());

        $this->input->setTemplateWidth($_templateWidth);
        $this->assertEquals($_templateWidth, $this->input->templateWidth());
    }

    public function setAttributesProvider()
    {
        return [
            ["test", 1, 2],
            ["myDirectory", 23, 76],
            ["randomDirectory", 45, 98],
            ["testThisDirectory", 33, 55]
        ];
    }

    /**
     * @covers \Input\FileInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $fileInputOptions = array(
            array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration")
        );

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
        $this->assertEquals(2, $this->input->templateWidth());
        $this->assertEquals(2, $this->input->templateHeight());

        $this->assertTrue($board[0][0]);
        $this->assertNull(@$board[0][1]);
        $this->assertNull(@$board[1][0]);
        $this->assertNull(@$board[1][1]);
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
        $this->optionsMock->expects($this->exactly(2))
                          ->method("getOption")
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
