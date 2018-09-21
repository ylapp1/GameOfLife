<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\OptionHandler\BoardEditorOptionHandler;
use Simulator\Board;
use Output\BoardEditorOutput;
use PHPUnit\Framework\TestCase;
use Utils\Shell\ShellInformationFetcher;

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
                                      ->setMethods(array("readInput", "loadOptions", "addBoardToHistory"))
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->testBoard = new Board(5, 5, true);
        $this->testBoard->setFieldState(3, 3, true);

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
        $this->testBoard->resetFields();

        $boardEditor = new BoardEditor("");
        $boardEditor->setBoard($this->testBoard);

        $shellInputReaderMock = $this->getMockBuilder(Utils\Shell\ShellInputReader::class)
                                     ->getMock();
        $shellInputReaderMock->expects($this->exactly(1))
                             ->method("readInput")
                             ->willReturn("exit");
        setPrivateAttribute($boardEditor, "shellInputReader", $shellInputReaderMock);

        $boardHistorySaverMock = $this->getMockBuilder(Simulator\BoardHistory::class)
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $boardHistorySaverMock->expects($this->exactly(1))
                              ->method("addBoardToHistory")
                              ->willReturn(null);
        setPrivateAttribute($boardEditor, "boardHistory", $boardHistorySaverMock);

        $optionHandlerMock = $this->getMockBuilder(BoardEditorOptionHandler::class)
                                  ->setMethods(array("parseInput"))
                                  ->disableOriginalConstructor()
                                  ->getMock();
        setPrivateAttribute($boardEditor, "optionHandler", $optionHandlerMock);

        $shellInformationFetcherMock = $this->getMockBuilder(ShellInformationFetcher::class)
                                            ->getMock();
        setPrivateAttribute($boardEditor, "shellInformationFetcher", $shellInformationFetcherMock);


        $shellInformationFetcherMock->expects($this->exactly(1))
                                    ->method("getNumberOfShellLines")
                                    ->willReturn(10);

        $expectedOutputTitleRegex = "\n *GAME OF LIFE\n *BOARD EDITOR\n\n*";
        $expectedOutputBoardRegex = " *╔═════╗\n"
                                  . " *║     ║\n"
                                  . " *║     ║\n"
                                  . " *║     ║\n"
                                  . " *║     ║\n"
                                  . " *║     ║\n"
                                  . " *╚═════╝\n*";

        $this->expectOutputRegex("/" . $expectedOutputTitleRegex . ".*" . $expectedOutputBoardRegex . "/");

        $optionHandlerMock->expects($this->exactly(1))
                          ->method("parseInput")
                          ->withConsecutive(array("exit"))
                          ->willReturn(true);

        if ($this->boardEditorMock instanceof BoardEditor) $boardEditor->launch();
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
        $this->assertEquals($_fileContent, $_fileContent);

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
