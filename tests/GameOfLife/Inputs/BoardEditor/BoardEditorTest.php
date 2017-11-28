<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;
use GameOfLife\Board;
use Input\TemplateHandler\TemplateSaver;
use Output\BoardEditorOutput;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether BoardEditor\BoardEditor works as expected.
 */
class BoardEditorTest extends TestCase
{
    /**
     * Test board editor mock
     * Using a mock to avoid the readInput function
     *
     * @var PHPUnit_Framework_MockObject_MockObject $boardEditorMock
     */
    private $boardEditorMock;

    /**
     * Test board
     *
     * @var Board $testBoard
     */
    private $testBoard;


    /**
     * Function that is called before each test.
     */
    public function setUp()
    {
        $this->boardEditorMock = $this->getMockBuilder(BoardEditor::class)
                                      ->setMethods(array("readInput", "loadOptions"))
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->testBoard = new Board(5, 5, 1, true);
        $this->testBoard->setField(3, 3, true);

        if ($this->boardEditorMock instanceof BoardEditor) $this->boardEditorMock->setBoard($this->testBoard);
        if ($this->boardEditorMock instanceof BoardEditor) $this->boardEditorMock->setOutput(new BoardEditorOutput());
    }

    /**
     * Function that is called after each test.
     */
    public function tearDown()
    {
        unset($this->boardEditorMock);
    }


    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\BoardEditor::__construct()
     */
    public function testCanBeConstructed()
    {
        $testTemplateDirectory = "testing";
        $boardEditor = new BoardEditor($testTemplateDirectory, $this->testBoard);

        $this->assertEquals(new BoardEditorOutput(), $boardEditor->output());
        $this->assertEquals(new TemplateSaver($testTemplateDirectory), $boardEditor->templateSaver());
        $this->assertEquals($this->testBoard, $boardEditor->board());
    }

    /**
     * Checks whether the getters and setters work as expected.
     *
     * @covers \BoardEditor\BoardEditor::board()
     * @covers \BoardEditor\BoardEditor::setBoard()
     * @covers \BoardEditor\BoardEditor::options()
     * @covers \BoardEditor\BoardEditor::setOptions()
     * @covers \BoardEditor\BoardEditor::output()
     * @covers \BoardEditor\BoardEditor::setOutput()
     * @covers \BoardEditor\BoardEditor::templateSaver()
     * @covers \BoardEditor\BoardEditor::setTemplateSaver()
     */
    public function testCanSetAttributes()
    {
        $testOptions = array("Hallo", "Zwei", "zahl", "Zehn");
        $boardEditor = new BoardEditor("hello");

        $boardEditor->setBoard($this->testBoard);
        $boardEditor->setOptions($testOptions);
        $boardEditor->setOutput(new BoardEditorOutput());
        $boardEditor->setTemplateSaver(new TemplateSaver("finalTest"));

        $this->assertEquals($this->testBoard, $boardEditor->board());
        $this->assertEquals($testOptions, $boardEditor->options());
        $this->assertEquals(new BoardEditorOutput(), $boardEditor->output());
        $this->assertEquals(new TemplateSaver("finalTest"), $boardEditor->templateSaver());
    }

    /**
     * Checks whether fields can be set with the board editor.
     *
     * @dataProvider setFieldsProvider()
     * @covers BoardEditor\BoardEditor::isOption()
     * @covers BoardEditor\BoardEditor::launch()
     * @covers BoardEditor\BoardEditor::setField()
     * @covers BoardEditor\BoardEditor::getInputCoordinate()
     *
     * @param String $_inputCoordinates Input coordinates
     * @param String $_expectedErrorMessage The expected error message
     */
    public function testCanSetField(String $_inputCoordinates, String $_expectedErrorMessage = null)
    {
        $this->testBoard->resetCurrentBoard();
        $this->boardEditorMock->expects($this->exactly(2))
            ->method("readInput")
            ->willReturn($_inputCoordinates, "start");

        if ($_expectedErrorMessage) $this->expectOutputRegex("/.*" . $_expectedErrorMessage . ".*/");
        else ($this->expectOutputRegex("/.*>.*/"));

        if ($this->boardEditorMock instanceof BoardEditor) $this->boardEditorMock->launch();

        if (! $_expectedErrorMessage)
        {
            $this->assertEquals(1, $this->testBoard->getAmountCellsAlive());
        }
    }

    /**
     * DataProvider for BoardEditorTest::testCanSetField().
     *
     * @return array Test values
     */
    public function setFieldsProvider()
    {
        return [
            "More than two values" => array("1,1,1,1,1,1,1,1,1", "Error: Please input exactly two values!\n"),
            "Less than two values" => array("1", "Error: Invalid option or invalid coordinates format\n"),
            "Exactly two values (1,1)" => array("1,1"),
            "Exactly two values (1,0)" => array("1, 0"),
            "Exactly two values (0,0)" => array("      0    ,      0    "),
            "X exceeds field dimensions" => array("-1, 0", "Error: Invalid value for x specified: Value exceeds field borders or is not set\n"),
            "Y empty" => array("4,", "Error: Invalid value for y specified: Value exceeds field borders or is not set\n")
        ];
    }

    /**
     * Checks whether the options from the option directory can be loaded.
     *
     * @covers BoardEditor\BoardEditor::callOption()
     * @covers BoardEditor\BoardEditor::isOption()
     * @covers BoardEditor\BoardEditor::loadOptions()
     * @covers BoardEditor\BoardEditor::launch()
     */
    public function testCanLoadOptions()
    {
        $this->boardEditorMock->expects($this->exactly(2))
            ->method("readInput")
            ->willReturn("options", "start");

        $expectedOutputRegex = "/";

        // Load each option from the options folder
        $classes = glob(__DIR__ . "/Options/*Option.php");

        foreach ($classes as $class)
        {
            $className = basename($class, ".php");
            $classPath = "BoardEditor\\Options\\" . $className;

            $instance = new $classPath($this);
            if ($instance instanceof BoardEditoroption) $expectedOutputRegex = ".*" . $instance->name() . ".*";
        }

        $expectedOutputRegex .= "/";

        $this->expectOutputRegex($expectedOutputRegex);

        if ($this->boardEditorMock instanceof BoardEditor) $this->boardEditorMock->launch();
    }

    /**
     * Checks whether the board editor can read the input from a text file.
     *
     * @dataProvider readInputProvider()
     * @covers \BoardEditor\BoardEditor::readInput()
     *
     * @param String $_fileContent Content that will be written to a test file which is read by readInput()
     */
    public function testCanReadInput(String $_fileContent)
    {
        $fileSystemHandler = new FileSystemHandler();
        $boardEditor = new BoardEditor("test", $this->testBoard);

        $testDirectory = __DIR__ . "/../userInputTest";
        $testFile = "testInput.txt";

        $fileSystemHandler->createDirectory($testDirectory);
        $fileSystemHandler->writeFile($testDirectory, $testFile, $_fileContent);

        $this->assertEquals($_fileContent, $boardEditor->readInput($testDirectory . "/" . $testFile));

        $fileSystemHandler->deleteDirectory($testDirectory, true);
    }

    /**
     * DataProvider for BoardEditorTest::testCanReadInput().
     *
     * @return array Test values
     */
    public function readInputProvider()
    {
        return [
            ["Hello universe!"],
            ["Hello world!"],
            ["Hallo Welt!"],
            ["Hallo Universum!!!"],
            ["Ich bin eine Datei"]
        ];
    }
}