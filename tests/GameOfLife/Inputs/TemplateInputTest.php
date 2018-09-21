<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\Coordinate;
use GameOfLife\Field;
use Input\TemplateInput;
use TemplateHandler\TemplateListPrinter;
use TemplateHandler\TemplateLoader;
use TemplateHandler\FieldsPlacer;
use Ulrichsg\Getopt;
use Utils\FileSystem\FileSystemWriter;
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

    /**
     * @throws Exception
     */
    protected function setUp()
    {
        $this->input = new TemplateInput($this->testTemplateDirectory);
        $this->board = new Board(10, 10, true);
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
     * Checks whether the constructor works as expected.
     *
     * @covers \Input\TemplateInput::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $input = new TemplateInput();

        $reflectionClass = new ReflectionClass(\Input\TemplateInput::class);

        $reflectionProperty = $reflectionClass->getProperty("templateLoader");
        $reflectionProperty->setAccessible(true);
        $this->assertInstanceOf(TemplateLoader::class, $reflectionProperty->getValue($input));

        $reflectionProperty = $reflectionClass->getProperty("templatePlacer");
        $reflectionProperty->setAccessible(true);
        $this->assertInstanceOf(FieldsPlacer::class, $reflectionProperty->getValue($input));
    }

    /**
     * @covers \Input\TemplateInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $templateOptions = array(
            array(null, "unittestPosX", Getopt::REQUIRED_ARGUMENT, "TemplateInput - X position of the unittest"),
            array(null, "unittestPosY", Getopt::REQUIRED_ARGUMENT, "TemplateInput - Y position of the unittest\n")
        );

        $fileInputOptions = array(
            array(null, "template", Getopt::REQUIRED_ARGUMENT, "TemplateInput - The name of the template file that shall be loaded"),
            array(null, "list-templates", Getopt::NO_ARGUMENT, "TemplateInput - Display a list of all templates"),
            array(null, "templatePosX", Getopt::REQUIRED_ARGUMENT, "TemplateInput - X-Position of the top left corner of the template"),
            array(null, "templatePosY", Getopt::REQUIRED_ARGUMENT, "TemplateInput - Y-Position of the top left corner of the template"),
            array(null, "invertTemplate", Getopt::NO_ARGUMENT, "TemplateInput - Inverts the loaded template\n")
        );

        $this->optionsMock->expects($this->exactly(2))
                          ->method("addOptions")
                          ->withConsecutive(array($templateOptions), array($fileInputOptions));
        if ($this->optionsMock instanceof Getopt) $this->input->addOptions($this->optionsMock);
    }

    /**
     * @covers \Input\TemplateInput::fillBoard
     * @covers \Input\TemplateInput::placeTemplate()
     *
     * @throws \Exception
     */
    public function testCanLoadTemplate()
    {
        $this->optionsMock->expects($this->exactly(9))
            ->method("getOption")
            ->withConsecutive(array("template"), array("template"), array("templatePosX"), array("templatePosY"),
                              array("templatePosX"), array("templatePosY"),array("width"), array("height"),
                              array("invertTemplate"))
            ->willReturn("unittest", "unittest", null, null, null, null , null, null, null);

        $field = new Field(new Coordinate(0, 0), false, $this->board);
        $field->setValue(true);

        $unitTestBoard = array(
            array(0 => $field,
                1 => new Field(new Coordinate(1, 0), false, $this->board)),
            array(0 => new Field(new Coordinate(0, 1), false, $this->board),
                1 => new Field(new Coordinate(1, 1), false, $this->board))
        );

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

        $this->assertEquals(2, $this->board->width());
        $this->assertEquals(2, $this->board->height());
        $this->assertEquals($unitTestBoard, $this->board->fields());
    }

    /**
     * @covers \Input\TemplateInput::fillBoard()
     *
     * @throws \Exception
     */
    public function testDetectsEmptyTemplateName()
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(6))
                    ->method("getOption")
                    ->withConsecutive(array("template"), array("list-templates"), array("input"), array("unittestPosX"),
                                      array("unittestPosY"), array("input"))
                    ->willReturn(null, null, null, null, null, "template");

        if ($optionsMock instanceof \Ulrichsg\Getopt)
        {
            $exceptionOccurred = false;
            try
            {
                $this->input->fillBoard($this->board, $optionsMock);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("No template file specified.", $_exception->getMessage());
            }
            $this->assertTrue($exceptionOccurred);
        }
    }

    /**
     * @dataProvider invalidTemplateNamesProvider
     *
     * @param string $_templateName     Name of the template
     *
     * @throws \Exception
     */
    public function testDetectsInvalidTemplateNames(string $_templateName)
    {
        $this->optionsMock->expects($this->exactly(2))
                          ->method("getOption")
                          ->withConsecutive(array("template"), array("template"))
                          ->willReturn($_templateName, $_templateName);

        if ($this->optionsMock instanceof Getopt)
        {
            $exceptionOccurred = false;
            try
            {
                $this->input->fillBoard($this->board, $this->optionsMock);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("The template file could not be found.", $_exception->getMessage());
            }

            $this->assertTrue($exceptionOccurred);
        }
    }

    public function invalidTemplateNamesProvider()
    {
        return [
            ["test"],
            ["theUnitTest"],
            ["notexisting"],
            ["mytemplate"],
            ["hello"]
        ];
    }

    /**
     * @covers \Input\TemplateInput::fillBoard()
     * @covers \TemplateHandler\TemplateListPrinter::printTemplateLists()
     * @covers \TemplateHandler\TemplateListPrinter::printTemplateList()
     *
     * @throws \Exception
     */
    public function testCanListTemplates()
    {
        $this->optionsMock->expects($this->exactly(4))
                          ->method("getOption")
                          ->withConsecutive(["template"], ["list-templates"], ["template"], ["list-templates"])
                          ->willReturn(null, true, null, true);

        $fileSystemHandler = new FileSystemWriter();

        try
        {
            $fileSystemHandler->createDirectory($this->testTemplateDirectory . "/Custom");
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }
        $fileSystemHandler->writeFile($this->testTemplateDirectory . "/Custom/mytest.txt", "");

        $expectedOutput = "Default templates:\n"
                        . "  1\) unittest\n\n"
                        . "Custom templates:\n"
                        . "  1\) mytest\n";

        $this->expectOutputRegex("/.*" . $expectedOutput . ".*/");
        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);
        $fileSystemHandler->deleteDirectory($this->testTemplateDirectory . "/Custom", true);

        $reflectionClass = new ReflectionClass(\Input\TemplateInput::class);

        $reflectionProperty = $reflectionClass->getProperty("templateListPrinter");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->input, new TemplateListPrinter(__DIR__));
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
     *
     * @throws \Exception
     */
    public function testCanPlaceTemplate(int $_posX, int $_posY, string $_expectedString = null)
    {
        $this->board->setWidth(10);
        $this->board->setWidth(10);
        $this->board->resetFields();

        $expectedAmountGetOptionCalls = 8;
        if ($_expectedString) $expectedAmountGetOptionCalls = 7;

        $this->optionsMock->expects($this->exactly($expectedAmountGetOptionCalls))
                          ->method("getOption")
                          ->withConsecutive(array("template"), array("template"), array("templatePosX"), array("templatePosX"),
                                            array("templatePosY"), array("templatePosY"), array("templatePosX"), array("invertTemplate"))
                          ->willReturn("unittest", "unittest", $_posX, $_posX, $_posY, $_posY, $_posX, null);

        $exceptionOccurred = false;
        if ($this->optionsMock instanceof Getopt)
        {
            try
            {
                $this->input->fillBoard($this->board, $this->optionsMock);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals($_expectedString, $_exception->getMessage());
            }
        }

        if ($_expectedString === null)
        {
            $this->assertEquals(1, $this->board->getNumberOfAliveFields());
            $this->assertTrue($this->board->getFieldState($_posX, $_posY));
            $this->assertFalse($exceptionOccurred);
        }
        else
        {
            $this->assertEquals(0, $this->board->getNumberOfAliveFields());
            $this->assertTrue($exceptionOccurred);
        }
    }

    public function placeTemplateProvider()
    {
        return [
            [9, 0, "The template exceeds the right border of the board."],
            [0 , 9, "The template exceeds the bottom border of the board."],
            [0 , -1, "The template exceeds the top border of the board."],
            [-1 , 0, "The template exceeds the left border of the board."],
            [0, 0],
            [1, 2]
        ];
    }

    /**
     * Checks whether a default template can be loaded by its linked options.
     *
     * @covers \Input\TemplateInput::fillBoard()
     * @covers \Input\TemplateInput::placeTemplate()
     * @covers \Input\TemplateInput::getTemplateNameFromLinkedOption()
     *
     * @throws \Exception
     */
    public function testCanLoadDefaultTemplatesFromLinkedOption()
    {
        $optionsMock = $this->getMockBuilder(Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(9))
            ->method("getOption")
            ->withConsecutive(
                array("template"), array("list-templates"), array("input"), array("unittestPosX"),
                array("unittestPosX"), array("unittestPosX"), array("unittestPosY"), array("unittestPosY"),
                array("invertTemplate")
            )
            ->willReturn(null, null, null, 5, 5, 5, 3, 3, null, null, null);

        $board = new Board(10, 8, true);

        if ($optionsMock instanceof \Ulrichsg\Getopt) $this->input->fillBoard($board, $optionsMock);

        $this->assertTrue($board->getFieldState(5, 3));
    }

    /**
     * Checks whether the random input is selected when an invalid input type is selected.
     *
     * @covers \Input\TemplateInput::fillBoard()
     *
     * @throws \Exception
     */
    public function testCanFallBackToRandomInput()
    {
        $optionsMock = $this->getMockBuilder(Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(5))
                    ->method("getOption")
                    ->withConsecutive(array("template"), array("list-templates"), array("input"), array("input"), array("input"))
                    ->willReturn(null, null, "test", "test", "test");

        $board = new Board(10, 8, true);

        if ($optionsMock instanceof \Ulrichsg\Getopt) $this->input->fillBoard($board, $optionsMock);

        $this->assertGreaterThanOrEqual(0.15, $board->getPercentageOfAliveFields());
        $this->assertLessThanOrEqual(0.70, $board->getPercentageOfAliveFields());
    }
}
