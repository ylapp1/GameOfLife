<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Input\UserInput;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\UserInput works as expected
 */
class UserInputTest extends TestCase
{
    /** @var Userinput $input */
    private $input;
    /** @var Board $board */
    private $board;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $userInputMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    private $testTemplatesDirectory = __DIR__ . "/../InputTemplates";

    protected function setUp()
    {
        $this->input = new UserInput();
        $this->input->setTemplateDirectory($this->testTemplatesDirectory);
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(2, 2, 50, true, $rules);
        $this->board->setField(0, 0, true);
        $this->fileSystemHandler = new FileSystemHandler();

        $this->userInputMock = $this->getMockBuilder(\Input\UserInput::class)
                                    ->setMethods(["catchUserInput"])
                                    ->getMock();
        if ($this->userInputMock instanceof UserInput) $this->userInputMock->setTemplateDirectory($this->testTemplatesDirectory);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->testTemplatesDirectory . "/Custom", true);

        unset($this->fileSystemHandler);
        unset($this->input);
        unset($this->board);
        unset($this->userInputMock);
        unset($this->optionsMock);
    }

    /**
     * @dataProvider setAttributesProvider()
     * @covers \Input\UserInput::templateDirectory()
     * @covers \Input\UserInput::setTemplateDirectory()
     *
     * @param string $_customTemplateDirectory    Directory where templates are saved
     */
    public function testCanSetAttributes(string $_customTemplateDirectory)
    {
        $this->input->setTemplateDirectory($_customTemplateDirectory);
        $this->assertEquals($_customTemplateDirectory, $this->input->templateDirectory());
    }

    public function setAttributesProvider()
    {
        return [
            ["test"],
            ["myTest"],
            ["mySpecialTest"]
        ];
    }

    /**
     * @covers \Input\UserInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $userInputOptions = array(
            array(null, "edit", Getopt::NO_ARGUMENT, "Edit a template")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($userInputOptions);
        if ($this->optionsMock instanceof Getopt) $this->input->addOptions($this->optionsMock);
    }

    /**
     * @covers \Input\UserInput::printBoardEditor()
     */
    public function testCanPrintBoardEditor()
    {
        $expectedString= "\n" .
                         " --\n" .
                         "|o |\n" .
                         "|  |\n" .
                         " --\n";

        $this->expectOutputString($expectedString);
        $this->input->printBoardEditor($this->board);


        $expectedString .= "\n" .
                           " 0\n" .
                           " ---\n" .
                           "|X| |0\n" .
                           "|---|\n" .
                           "| | |\n" .
                           " ---\n";

        $this->expectOutputString($expectedString);
        $this->input->printBoardEditor($this->board, 0, 0);
    }

    /**
     * @covers \Input\UserInput::saveCustomTemplate()
     */
    public function testCanSaveCustomTemplate()
    {
        $this->expectOutputRegex("/.*Template successfully saved!\n\n.*/");
        $this->input->saveCustomTemplate("unitTest", $this->board);
        $this->assertTrue(file_exists($this->testTemplatesDirectory . "/Custom/unitTest.txt"));

        $this->userInputMock->expects($this->exactly(3))
                            ->method("catchUserInput")
                            ->withConsecutive()
                            ->willReturn("n", "y", "yes");

        if ($this->userInputMock instanceof UserInput)
        {
            $this->expectOutputRegex("/.*Saving aborted.\n\n.*/");
            $this->userInputMock->saveCustomTemplate("unitTest", $this->board);

            $this->expectOutputRegex("/.*Template successfully replaced!\n\n.*/");
            $this->userInputMock->saveCustomTemplate("unitTest", $this->board);

            $this->expectOutputRegex("/.*Template successfully replaced!\n\n.*/");
            $this->userInputMock->saveCustomTemplate("unitTest", $this->board);
        }
    }


    /**
     * @dataProvider getInputCoordinatesProvider
     * @covers \Input\UserInput::getInputCoordinate()
     *
     * @param int $_expected
     * @param String $_testInput    Single coordinate in a string
     * @param int $_minValue
     * @param int $_maxValue
     */
    public function testCanGetInputCoordinates(int $_expected, String $_testInput, int $_minValue, int $_maxValue)
    {
        $this->assertEquals($_expected, $this->input->getInputCoordinate($_testInput, $_minValue, $_maxValue));
    }

    public function getInputCoordinatesProvider()
    {
        return [
            [false, "a", 0, 10],
            [false, "kjahfaksdhfyj", 1, 4],
            [1, "1", 0, 10],
            [false, "1", 5, 10],
            [false, "10", 1, 5]
        ];
    }

    /**
     * @dataProvider processInputProvider()
     * @covers \Input\UserInput::processInput()
     *
     * @param bool $_expected   Expected return value
     * @param string $_input    User input
     * @param string $_expectOutputString   Expected output string
     */
    public function testCanProcessInput(bool $_expected, string $_input, string $_expectOutputString = null)
    {
        if (isset($_expectOutputString)) $this->expectOutputString($_expectOutputString);
        $this->assertEquals($_expected, $this->input->processInput($_input, $this->board));
    }

    public function processInputProvider()
    {
        return [
            [true, "exit"],
            [true, "exit dkfjghdkfjgskfghsdk dksfjghsdfkgjs dfhgkjsdhfg ksdfjg sdfhgkjh"],
            [true, "start"],
            [true, "start kdasfjhasdkf asdhkf jasdkhfjasd kf"],
            [true, "startjkjjjkkjkjkj"],
            [false, "save", "Error: Invalid template name!\n"],
            [false, "save my file is test.txt", "Error: Invalid template name!\n"],
            [false, "1,1", "\n" .
                           "  1\n" .
                           " ----\n" .
                           "|o| |\n" .
                           "|----|\n" .
                           "| |X|1\n" .
                           " ----\n"
            ],
            [false, "1,", "Error: Invalid value for y specified: Value exceeds field borders or is not set\n"],
            [false, ",1", "Error: Invalid value for x specified: Value exceeds field borders or is not set\n"],
            [false, "1,1,1", "Error: Please input exactly two values!\n"],
            [false, "aasaaa", "Error: Input the coordinates in this format: <x" . ">,<y" . ">\n"],
            [false, "save atest", "Template successfully saved!\n\nYou can set/unset more cells or start the simulation by typing \"start\"\n\n"]
        ];
    }

    /**
     * @dataProvider setFieldProvider
     * @covers \Input\UserInput::setField()
     *
     * @param String $_inputCoordinates     Input coordinates in the format <x>,<y>
     * @param String $_expectedString       Expected Error message
     * @param int $_expectedX               Expected x position of new set cell
     * @param int $_expectedY               Expected y positoin of new set cell
     */
    public function testCanSetField(String $_inputCoordinates, String $_expectedString, int $_expectedX = null, int $_expectedY = null)
    {
        $this->board->setCurrentBoard($this->board->initializeEmptyBoard());
        $this->expectOutputRegex("/.*" . $_expectedString . ".*/");
        $this->input->setField($this->board, $_inputCoordinates);

        if (isset($_expectedX) && isset($_expectedY)) $this->assertEquals(true, $this->board->getField($_expectedX, $_expectedY));
    }

    public function setFieldProvider()
    {
        return [
            "More than two values" => ["1,1,1,1,1,1,1,1,1", "Error: Please input exactly two values!\n"],
            "Less than two values" => ["1", "Error: Please input exactly two values!\n"],
            "Exactly two values (1,1)" => ["1,1", "1", 1, 1],
            "Exactly two values (1,0)" => ["1, 0", "1", 1, 0],
            "Exactly two values (0,0)" => ["      0    ,      0    ", "0", 0, 0]
        ];
    }


    /**
     * @dataProvider fillBoardProvider()
     * @covers \Input\UserInput::fillBoard()
     *
     * @param int $_inputX  X Coordinate of user input
     * @param int $_inputY  Y Coordinate of user input
     */
    public function testCanFillBoard(int $_inputX, int $_inputY)
    {
        $this->userInputMock->expects($this->exactly(2))
                            ->method("catchUserInput")
                            ->withConsecutive()
                            ->willReturn($_inputX . "," . $_inputY, "start");

        $this->userInputMock->expects($this->exactly(2))
                            ->method("catchUserInput");

        $this->board->setCurrentBoard($this->board->initializeEmptyBoard());

        $expectedOutputRegex =  "Set the coordinates for the living cells as below:\n" .
                                 "<X-Coordinate" . ">,<Y-Coordinate" . ">\n" .
                                 "Enter the coordinates of a set field to unset it.\n" .
                                 "The game starts when you type \"start\" in a new line and press <"."Enter>\n" .
                                 "You can save your board configuration before starting the simulation by typing \"save\"\n" .
                                 "Let's Go:\n";
        $this->expectOutputRegex("/.*" . $expectedOutputRegex . ".*/");
        if ($this->userInputMock instanceof UserInput) $this->userInputMock->fillBoard($this->board, new Getopt());

        $this->assertEquals(1, $this->board->getAmountCellsAlive());
        $this->assertTrue($this->board->getField($_inputX, $_inputY));
    }

    public function fillBoardProvider()
    {
        return [
            [0, 0],
            [0, 1],
            [1, 1]
        ];
    }

    /**
     * @covers \Input\UserInput::fillBoard()
     */
    public function testCanLoadTemplate()
    {
        $this->userInputMock->method("catchUserInput")
                            ->willReturn("start");

        $this->userInputMock->expects($this->exactly(1))
                            ->method("catchUserInput");

        $this->board->setCurrentBoard($this->board->initializeEmptyBoard());
        $this->assertEquals(0, $this->board->getAmountCellsAlive());

        $this->optionsMock->expects($this->exactly(3))
                          ->method("getOption")
                          ->withConsecutive(["edit"], ["template"], ["template"])
                          ->willReturn(true, "unittest", "unittest");

        $expectedOutputRegex =  "Set the coordinates for the living cells as below:\n" .
                                "<X-Coordinate" . ">,<Y-Coordinate" . ">\n" .
                                "Enter the coordinates of a set field to unset it.\n" .
                                "The game starts when you type \"start\" in a new line and press <"."Enter>\n" .
                                "You can save your board configuration before starting the simulation by typing \"save\"\n" .
                                "Let's Go:\n";
        $this->expectOutputRegex("/.*" . $expectedOutputRegex . ".*/");
        if ( $this->userInputMock instanceof UserInput)
        {
            if ($this->optionsMock instanceof Getopt) $this->userInputMock->fillBoard($this->board, $this->optionsMock);
        }

        $this->assertEquals(1, $this->board->getAmountCellsAlive());
        $this->assertTrue($this->board->getField(0, 0));
        $this->assertEquals(2, $this->board->width());
        $this->assertEquals(2, $this->board->height());
    }

    /**
     * @dataProvider catchUserInputProvider
     * @covers \Input\UserInput::catchUserInput()
     *
     * @param string $_fileContent      Content that will be written to a test file which is read by catchUserInput
     */
    public function testCanCatchUserInput(string $_fileContent)
    {
        $testDirectory = __DIR__ . "/../userInputTest";
        $this->fileSystemHandler->createDirectory($testDirectory);

        $testFile = "testInput.txt";
        $this->fileSystemHandler->writeFile($testDirectory, $testFile, $_fileContent);

        $this->assertEquals($_fileContent, $this->input->catchUserInput($testDirectory . "/" . $testFile));

        $this->fileSystemHandler->deleteDirectory($testDirectory, true);
    }

    public function catchUserInputProvider()
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