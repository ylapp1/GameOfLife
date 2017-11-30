<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\Field;
use Input\FileInput;
use Input\TemplateHandler\TemplateLoader;
use Input\TemplateHandler\TemplatePlacer;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\FileInput works as expected.
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
        $this->input = new FileInput($this->testTemplateDirectory);
        $this->board = new Board(10, 10, 50, true);
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
     * @covers \Input\FileInput::setTemplateLoader()
     * @covers \Input\FileInput::templateLoader()
     * @covers \Input\FileInput::setTemplatePlacer()
     * @covers \Input\FileInput::templatePlacer()
     *
     * @param string $_templateDirectory    Template directory
     */
    public function testCanSetAttributes(string $_templateDirectory)
    {
        $fileSystemHandler = new FileSystemHandler();
        $templateLoader = new TemplateLoader($_templateDirectory);
        $templatePlacer = new TemplatePlacer();

        $this->input->setFileSystemHandler($fileSystemHandler);
        $this->input->setTemplateDirectory($_templateDirectory);
        $this->input->setTemplateLoader($templateLoader);
        $this->input->setTemplatePlacer($templatePlacer);

        $this->assertEquals($fileSystemHandler, $this->input->fileSystemHandler());
        $this->assertEquals($_templateDirectory, $this->input->templateDirectory());
        $this->assertEquals($templateLoader, $this->input->templateLoader());
        $this->assertEquals($templatePlacer, $this->input->templatePlacer());
    }

    public function setAttributesProvider()
    {
        return [
            ["test"],
            ["myDirectory"],
            ["randomDirectory"],
            ["testThisDirectory"]
        ];
    }

    /**
     * @covers \Input\FileInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $fileInputOptions = array(
            array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration"),
            array(null, "list-templates", Getopt::NO_ARGUMENT, "Display a list of all templates"),
            array(null, "templatePosX", Getopt::REQUIRED_ARGUMENT, "X-Position of the top left corner of the template"),
            array(null, "templatePosY", Getopt::REQUIRED_ARGUMENT, "Y-Position of the top left corner of the template"),
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($fileInputOptions);
        if ($this->optionsMock instanceof Getopt) $this->input->addOptions($this->optionsMock);
    }

    /**
     * @covers \Input\FileInput::fillBoard
     * @covers \Input\FileInput::placeTemplate()
     */
    public function testCanLoadTemplate()
    {
        $this->optionsMock->expects($this->exactly(4))
            ->method("getOption")
            ->withConsecutive(["template"], ["templatePosX"], ["templatePosY"], ["template"])
            ->willReturn("unittest",null, null, "unittest");

        $field = new Field($this->board, 0, 0);
        $field->setValue(true);

        $unitTestBoard = array(
            array(0 => $field,
                1 => new Field($this->board, 1, 0)),
            array(0 => new Field($this->board,0, 1),
                1 => new Field($this->board,1, 1))
        );

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

        $this->assertEquals(2, $this->board->width());
        $this->assertEquals(2, $this->board->height());
        $this->assertEquals($unitTestBoard, $this->board->fields());
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
     *
     * @param string $_templateName     Name of the template
     */
    public function testDetectsInvalidTemplateNames(string $_templateName)
    {
        $this->optionsMock->expects($this->exactly(4))
                          ->method("getOption")
                          ->withConsecutive(["template"], ["templatePosX"], ["templatePosY"], ["template"])
                          ->willReturn($_templateName,null, null, $_templateName);

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
     * @covers \Input\FileInput::listTemplates()
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
        $fileSystemHandler->deleteDirectory($this->testTemplateDirectory . "/Custom", true);

        $this->input->setTemplateDirectory(__DIR__);
        $expectedOutput = "Default templates:\n"
            . "  None\n\n"
            . "Custom templates:\n"
            . "  None\n";

        $this->expectOutputRegex("/.*" . $expectedOutput . ".*/");
        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);
    }

    /**
     * @dataProvider placeTemplateProvider()
     * @covers \Input\FileInput::fillBoard()
     * @covers \Input\FileInput::placeTemplate()
     *
     * @param int $_posX                X-Position of top left corner of the template
     * @param int $_posY                Y-Position of top left corner of the template
     * @param string $_expectedString   Expected error message
     */
    public function testCanPlaceTemplate(int $_posX, int $_posY, string $_expectedString = null)
    {
        $this->board->setWidth(10);
        $this->board->setWidth(10);
        $this->board->resetBoard();

        $this->optionsMock->expects($this->exactly(6))
                          ->method("getOption")
                          ->withConsecutive(["template"], ["templatePosX"], ["templatePosX"], ["templatePosY"], ["templatePosY"], ["template"])
                          ->willReturn("unittest", $_posX, $_posX, $_posY, $_posY, "unittest");

        if ($_expectedString !== null) $this->expectOutputString($_expectedString);

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

        if ($_expectedString === null)
        {
            $this->assertEquals(1, $this->board->getAmountCellsAlive());
            $this->assertTrue($this->board->getField($_posX, $_posY));
        }
    }

    public function placeTemplateProvider()
    {
        $error = "Error, the template may not exceed the field borders!\n";

        return [
            [9, 0, $error],
            [0 , 9, $error],
            [0 , -1, $error],
            [-1 , 0, $error],
            [0, 0],
            [1, 2]
        ];
    }
}