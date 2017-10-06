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
use Utils\FileSystemHandler;
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
    private $testTemplateDirectory = __DIR__ . "/../InputTemplates/";

    protected function setUp()
    {
        $this->input = new FileInput();
        $this->input->setTemplateDirectory($this->testTemplateDirectory);
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
     * @covers \Input\FileInput::__construct()
     */
    public function testCanBeConstructed()
    {
        $input = new FileInput();
        $this->assertEquals(new FileSystemHandler(), $input->fileSystemhandler());
    }

    /**
     * @dataProvider setAttributesProvider
     * @covers \Input\FileInput::setFileSystemHandler()
     * @covers \Input\FileInput::fileSystemHandler()
     * @covers \Input\FileInput::setTemplateDirectory()
     * @covers \Input\FileInput::templateDirectory()
     * @covers \Input\FileInput::setTemplateHeight()
     * @covers \Input\FileInput::templateHeight()
     * @covers \Input\FileInput::setTemplateWidth()
     * @covers \Input\FileInput::templateWidth()
     *
     * @param string $_templateDirectory    Template directory
     * @param int $_templateWidth           New width of board after reading template
     * @param int $_templateHeight          New height of board after reading template
     */
    public function testCanSetAttributes(string $_templateDirectory, int $_templateWidth, int $_templateHeight)
    {
        $fileSystemHandler = new FileSystemHandler();
        $this->input->setFileSystemHandler($fileSystemHandler);
        $this->assertEquals($fileSystemHandler, $this->input->fileSystemHandler());

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
            array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration"),
            array(null, "list-templates", Getopt::NO_ARGUMENT, "Display a list of all templates")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($fileInputOptions);
        if ($this->optionsMock instanceof Getopt) $this->input->addOptions($this->optionsMock);
    }

    /**
     * @covers \Input\FileInput::fillBoard
     * @covers \Input\FileInput::loadTemplate()
     */
    public function testCanLoadTemplate()
    {
        $this->optionsMock->expects($this->exactly(2))
                          ->method("getOption")
                          ->with("template")
                          ->willReturn("unittest");

        $unitTestBoard = array(
            array(0 => true),
            array()
        );

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

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

    /**
     * @dataProvider invalidTemplateNamesProvider
     * @covers \Input\FileInput::loadTemplate()
     *
     * @param string $_templateName     Name of the template
     */
    public function testDetectsInvalidTemplateNames(string $_templateName)
    {
        $this->optionsMock->expects($this->exactly(2))
                          ->method("getOption")
                          ->with("template")
                          ->willReturn($_templateName);

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);
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
     * @covers \Input\FileInput::fillBoard()
     */
    public function testCanListTemplates()
    {
        $this->optionsMock->expects($this->exactly(4))
                          ->method("getOption")
                          ->withConsecutive(["template"], ["list-templates"], ["template"], ["list-templates"])
                          ->willReturn(null, true, null, true);

        $fileSystemHandler = new FileSystemHandler();
        $fileSystemHandler->createDirectory($this->testTemplateDirectory . "/Custom");
        touch($this->testTemplateDirectory . "/Custom/mytest.txt");

        $expectedOutput = "Default templates:\n"
                        . "  1\) unittest\n\n"
                        . "Custom templates:\n"
                        . "  1\) mytest\n";

        $this->expectOutputRegex("/.*" . $expectedOutput . ".*/");
        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);
        $fileSystemHandler->deleteDirectory($this->testTemplateDirectory . "/Custom");

        $this->input->setTemplateDirectory(__DIR__);
        $expectedOutput = "Default templates:\n"
            . "  None\n\n"
            . "Custom templates:\n"
            . "  None\n";

        $this->expectOutputRegex("/.*" . $expectedOutput . ".*/");
        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);
    }
}