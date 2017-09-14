<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use GameOfLife\Board;
use GameOfLife\RuleSet;
use GameOfLife\FileSystemHandler;
use Input\UserInput;
use Ulrichsg\Getopt;

/**
 * Class UserInputTest
 */
class UserInputTest extends TestCase
{
    /** @var Userinput $input */
    private $input;
    /** @var Board $board */
    private $board;

    protected function setUp()
    {
        $this->input = new UserInput();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(2, 2, 50, true, $rules);
        $this->board->setField(0, 0, true);
    }

    protected function tearDown()
    {
        unset($this->input);
        unset($this->board);
    }

    /**
     * @covers \Input\UserInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $options = new Getopt();
        $this->input->addOptions($options);
        $optionList = $options->getOptionList();

        $this->assertEquals(1, count($optionList));
        $this->assertContains("edit", $optionList[0]);
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

        $this->assertEquals(true, file_exists(__DIR__ . "/../../Input/Templates/Custom/unitTest.txt"));

        $fileSystemHandler = new FileSystemHandler();
        $fileSystemHandler->deleteDirectory(__DIR__ . "/../../Input/Templates/Custom", true);
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
            [false, "1,1", "\n  1\n ----\n|o| |\n|----|\n| |X|1\n ----\n"],
            [false, "1,", "Error: Invalid value for y specified: Value exceeds field borders or is not set\n"],
            [false, ",1", "Error: Invalid value for x specified: Value exceeds field borders or is not set\n"],
            [false, "1,1,1", "Error: Don't input more than two values!\n"],
            [false, "aasaaa", "Error: Input the coordinates in this format: <x" . ">,<y" . ">\n"]
        ];
    }
}