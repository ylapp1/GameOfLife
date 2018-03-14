<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\Field;
use Input\TemplateInput;
use TemplateHandler\TemplateLoader;
use TemplateHandler\TemplatePlacer;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\TemplateInput works as expected.
 */
class TemplateInputTest extends TestCase
{
    /** @var  TemplateInput $input */
    private $input;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;
    private $testTemplateDirectory = __DIR__ . "/../InputTemplates/";

    protected function setUp()
    {
        $this->input = new TemplateInput($this->testTemplateDirectory);
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
     * @covers \Input\TemplateInput::__construct()
     */
    public function testCanBeConstructed()
    {
        $input = new TemplateInput();

        $this->assertInstanceOf(TemplateLoader::class, $input->templateLoader());
        $this->assertInstanceOf(TemplatePlacer::class, $input->templatePlacer());
    }

    /**
     * @dataProvider setAttributesProvider
     * @covers \Input\TemplateInput::setTemplateDirectory()
     * @covers \Input\TemplateInput::templateDirectory()
     * @covers \Input\TemplateInput::setTemplateLoader()
     * @covers \Input\TemplateInput::templateLoader()
     * @covers \Input\TemplateInput::setTemplatePlacer()
     * @covers \Input\TemplateInput::templatePlacer()
     *
     * @param string $_templateDirectory    Template directory
     */
    public function testCanSetAttributes(string $_templateDirectory)
    {
        $templateLoader = new TemplateLoader($_templateDirectory);
        $templatePlacer = new TemplatePlacer();

        $this->input->setTemplateDirectory($_templateDirectory);
        $this->input->setTemplateLoader($templateLoader);
        $this->input->setTemplatePlacer($templatePlacer);

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
     * @covers \Input\TemplateInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $templateOptions = array(
            array(null, "unittestPosX", Getopt::REQUIRED_ARGUMENT, "X position of the unittest"),
            array(null, "unittestPosY", Getopt::REQUIRED_ARGUMENT, "Y position of the unittest")
        );

        $fileInputOptions = array(
            array(null, "template", Getopt::REQUIRED_ARGUMENT, "Txt file that stores the board configuration"),
            array(null, "list-templates", Getopt::NO_ARGUMENT, "Display a list of all templates"),
            array(null, "templatePosX", Getopt::REQUIRED_ARGUMENT, "X-Position of the top left corner of the template"),
            array(null, "templatePosY", Getopt::REQUIRED_ARGUMENT, "Y-Position of the top left corner of the template"),
        );

        $this->optionsMock->expects($this->exactly(2))
                          ->method("addOptions")
                          ->withConsecutive(array($templateOptions), array($fileInputOptions));
        if ($this->optionsMock instanceof Getopt) $this->input->addOptions($this->optionsMock);
    }

    /**
     * @covers \Input\TemplateInput::fillBoard
     * @covers \Input\TemplateInput::placeTemplate()
     */
    public function testCanLoadTemplate()
    {
        $this->optionsMock->expects($this->exactly(6))
            ->method("getOption")
            ->withConsecutive(array("template"), array("templatePosX"), array("templatePosY"), array("width"), array("height"), array("template"))
            ->willReturn("unittest", null, null, null, null, "unittest");

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
     * @covers \Input\TemplateInput::fillBoard()
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
        $this->optionsMock->expects($this->exactly(6))
                          ->method("getOption")
                          ->withConsecutive(array("template"), array("templatePosX"), array("templatePosY"), array("width"), array("height"), array("template"))
                          ->willReturn($_templateName,null, null, null, null, $_templateName);

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
     * @covers \Input\TemplateInput::fillBoard()
     * @covers \Input\TemplateInput::listTemplates()
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
     * @covers \Input\TemplateInput::fillBoard()
     * @covers \Input\TemplateInput::placeTemplate()
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

        $this->optionsMock->expects($this->exactly(8))
                          ->method("getOption")
                          ->withConsecutive(array("template"), array("templatePosX"), array("templatePosX"), array("templatePosY"),
                                            array("templatePosY"), array("width"), array("height"), array("template"))
                          ->willReturn("unittest", $_posX, $_posX, $_posY, $_posY, null, null, "unittest");

        if ($_expectedString !== null) $this->expectOutputString($_expectedString);

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

        if ($_expectedString === null)
        {
            $this->assertEquals(1, $this->board->getAmountCellsAlive());
            $this->assertTrue($this->board->getFieldStatus($_posX, $_posY));
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

    /**
     * Checks whether a default template can be loaded by its linked options.
     *
     * @covers \Input\TemplateInput::fillBoard()
     * @covers \Input\TemplateInput::placeTemplate()
     */
    public function testCanLoadDefaultTemplatesFromLinkedOption()
    {
        $optionsMock = $this->getMockBuilder(Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(10))
            ->method("getOption")
            ->withConsecutive(
                array("template"), array("list-templates"), array("input"), array("unittestPosX"),
                array("unittestPosX"), array("unittestPosX"), array("unittestPosY"), array("unittestPosY"), array("width"), array("height")
            )
            ->willReturn(null, null, null, 5, 5, 5, 3, 3, null, null);

        $board = new Board(10, 8, 5, true);

        if ($optionsMock instanceof \Ulrichsg\Getopt) $this->input->fillBoard($board, $optionsMock);

        $this->assertTrue($board->getFieldStatus(5, 3));
    }

    /**
     * Checks whether the random input is selected when an invalid input type is selected.
     *
     * @covers \Input\TemplateInput::fillBoard()
     */
    public function testCanFallBackToRandomInput()
    {
        $optionsMock = $this->getMockBuilder(Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(6))
                    ->method("getOption")
                    ->withConsecutive(array("template"), array("list-templates"), array("input"), array("input"), array("input"), array("input"))
                    ->willReturn(null, null, "test", "test", "test", "test");

        $board = new Board(10, 8, 5, true);

        if ($optionsMock instanceof \Ulrichsg\Getopt) $this->input->fillBoard($board, $optionsMock);

        $this->assertGreaterThan(0.15, $board->getFillPercentage());
        $this->assertLessThan(0.70, $board->getFillPercentage());
    }
}
