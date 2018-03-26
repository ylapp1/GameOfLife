<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use GameOfLife\Board;
use Input\UserInput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\UserInput works as expected.
 */
class UserInputTest extends TestCase
{
    /**
     * Test user input
     *
     * @var Userinput $input
     */
    private $input;

    /**
     * Test board
     *
     * @var Board $board
     */
    private $board;

    /**
     * Test board editor
     *
     * @var PHPUnit_Framework_MockObject_MockObject $boardEditorMock
     */
    private $boardEditorMock;

    /**
     * Test options
     *
     * @var \PHPUnit_Framework_MockObject_MockObject $optionsMock
     */
    private $optionsMock;

    /**
     * Template directory for unit tests
     *
     * @var string $testTemplatesDirectory
     */
    private $testTemplatesDirectory = __DIR__ . "/../InputTemplates";



    /**
     * Function that is called before and after each test.
     *
     * @throws \Exception
     */
    protected function setUp()
    {
        $this->board = new Board(2, 2, 50, true);
        $this->boardEditorMock = $this->getMockBuilder(BoardEditor::class)
            ->setMethods(["launch"])
            ->setConstructorArgs(array("test", $this->board))
            ->getMock();
        $this->input = new UserInput();
        $this->optionsMock = $this->getMockBuilder(Getopt::class)
                                  ->getMock();

        if ($this->boardEditorMock instanceof BoardEditor)
        {
            $reflectionClass = new ReflectionClass(\Input\UserInput::class);

            $reflectionProperty = $reflectionClass->getProperty("boardEditor");
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($this->input, $this->boardEditorMock);

            $reflectionProperty = $reflectionClass->getProperty("templatesBaseDirectory");
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($this->input, $this->testTemplatesDirectory);
        }
    }

    /**
     * Function that is called before and after each test.
     */
    protected function tearDown()
    {
        unset($this->input);
        unset($this->board);
        unset($this->boardEditorMock);
        unset($this->optionsMock);
    }


    /**
     * Checks whether the Getopt options are set correctly.
     *
     * @covers \Input\UserInput::addOptions()
     * @covers \Input\UserInput::__construct()
     */
    public function testCanAddOptions()
    {
        $userInputOptions = array(
            array(null, "edit", Getopt::NO_ARGUMENT, "Edit a template\n")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($userInputOptions);
        if ($this->optionsMock instanceof Getopt) $this->input->addOptions($this->optionsMock);
    }

    /**
     * Checks whether a template can be loaded using the --edit option.
     *
     * @covers \Input\UserInput::fillBoard()
     *
     * @throws \Exception
     */
    public function testCanLoadTemplate()
    {
        $this->optionsMock->expects($this->exactly(10))
                          ->method("getOption")
                          ->withConsecutive(array("edit"), array("template"), array("template"), array("templatePosX"),
                                            array("templatePosY"), array("templatePosX"), array("templatePosY"),
                                            array("width"), array("height"), array("invertTemplate"))
                          ->willReturn(true, "unittest", "unittest", null, null, null, null, null, null, null);
        $this->boardEditorMock->expects($this->exactly(1))
                              ->method("launch")
                              ->will($this->returnValue(null));

        if ($this->optionsMock instanceof Getopt) $this->input->fillBoard($this->board, $this->optionsMock);

        $this->assertEquals(1, $this->board->getAmountCellsAlive());
        $this->assertTrue($this->board->getFieldStatus(0, 0));
        $this->assertEquals(2, $this->board->width());
        $this->assertEquals(2, $this->board->height());
    }
}
