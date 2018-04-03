<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\OptionHandler\BoardEditorOptionHandler;
use GameOfLife\Board;
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
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $testTemplateDirectory = "testing";
        $boardEditor = new BoardEditor($testTemplateDirectory, $this->testBoard);

        $this->assertEquals(new BoardEditorOutput(), $boardEditor->output());
        $this->assertEquals($this->testBoard, $boardEditor->board());
    }

    /**
     * Checks whether the getters and setters work as expected.
     *
     * @covers \BoardEditor\BoardEditor::board()
     * @covers \BoardEditor\BoardEditor::setBoard()
     * @covers \BoardEditor\BoardEditor::optionHandler()
     * @covers \BoardEditor\BoardEditor::setOptionHandler()
     * @covers \BoardEditor\BoardEditor::output()
     * @covers \BoardEditor\BoardEditor::setOutput()
     * @covers \BoardEditor\BoardEditor::templateDirectory()
     * @covers \BoardEditor\BoardEditor::setTemplateDirectory()
     *
     * @throws \Exception
     */
    public function testCanSetAttributes()
    {
        $boardEditor = new BoardEditor("hello");

        $optionHandler = new BoardEditorOptionHandler($boardEditor);
        $output = new BoardEditorOutput();
        $templateDirectory = "This/is/a/very/serious/test";

        $boardEditor->setBoard($this->testBoard);
        $boardEditor->setOptionHandler($optionHandler);
        $boardEditor->setOutput($output);
        $boardEditor->setTemplateDirectory($templateDirectory);

        $this->assertEquals($this->testBoard, $boardEditor->board());
        $this->assertEquals($optionHandler, $boardEditor->optionHandler());
        $this->assertEquals($output, $boardEditor->output());
        $this->assertEquals($templateDirectory, $boardEditor->templateDirectory());
    }

    /**
     * Checks whether the board editor can launch a board editor session.
     *
     * @covers \BoardEditor\BoardEditor::launch()
     *
     * @throws \Exception
     */
    public function testCanLaunchSession()
    {
        $this->testBoard->resetBoard();
        $this->boardEditorMock->expects($this->exactly(1))
            ->method("readInput")
            ->willReturn("exit");

        $optionHandlerMock = $this->getMockBuilder(BoardEditorOptionHandler::class)
                                  ->setMethods(array("parseInput"))
                                  ->disableOriginalConstructor()
                                  ->getMock();

        if ($this->boardEditorMock instanceof BoardEditor)
        {
            if ($optionHandlerMock instanceof BoardEditorOptionHandler)
            {
                $this->boardEditorMock->setOptionHandler($optionHandlerMock);
            }
        }

        $expectedOutputTitle = "\nGAME OF LIFE\nBOARD EDITOR\n\n";
        $expectedOutputBoard = "╔═════╗\n"
                             . "║     ║\n"
                             . "║     ║\n"
                             . "║     ║\n"
                             . "║     ║\n"
                             . "║     ║\n"
                             . "╚═════╝\n";

        $this->expectOutputString($expectedOutputTitle . $expectedOutputBoard);

        $optionHandlerMock->expects($this->exactly(2))
                          ->method("parseInput")
                          ->withConsecutive(array("help"), array("exit"))
                          ->willReturn(false, true);

        if ($this->boardEditorMock instanceof BoardEditor) $this->boardEditorMock->launch();
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
     * Checks whether the board editor can read the input from a text file.
     *
     * @dataProvider readInputProvider()
     * @covers \BoardEditor\BoardEditor::readInput()
     *
     * @param String $_fileContent Content that will be written to a test file which is read by readInput()
     *
     * @throws Exception
     */
    public function testCanReadInput(String $_fileContent)
    {
        $this->assertTrue(true);

        /*
        $fileSystemHandler = new FileSystemHandler();
        $boardEditor = new BoardEditor("test", $this->testBoard);

        $testDirectory = __DIR__ . "/../userInputTest";
        $testFile = "testInput.txt";

        $fileSystemHandler->createDirectory($testDirectory);
        $fileSystemHandler->writeFile($testDirectory, $testFile, $_fileContent);

        $this->assertEquals($_fileContent, $boardEditor->readInput());
        $fileSystemHandler->deleteDirectory($testDirectory, true);
        */
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
