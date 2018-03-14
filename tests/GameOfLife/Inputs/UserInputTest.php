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

        if ($this->boardEditorMock instanceof BoardEditor) $this->input->setBoardEditor($this->boardEditorMock);
        $this->input->setTemplateDirectory($this->testTemplatesDirectory);
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
     * Checks whether the constructor works as expected.
     *
     * @covers \Input\UserInput::__construct()
     */
    public function testCanBeConstructed()
    {
        $input = new UserInput();

        $this->assertInstanceOf(BoardEditor::class, $input->boardEditor());
    }

    /**
     * Checks whether the getters and setters work as expected.
     *
     * @dataProvider setAttributesProvider()
     * @covers \Input\UserInput::templateDirectory()
     * @covers \Input\UserInput::setTemplateDirectory()
     * @covers \Input\UserInput::boardEditor()
     * @covers \Input\UserInput::setBoardEditor()
     *
     * @param string $_customTemplateDirectory Directory where templates are saved
     */
    public function testCanSetAttributes(string $_customTemplateDirectory)
    {
        $boardEditor = new BoardEditor($_customTemplateDirectory, $this->board);

        $this->input->setTemplateDirectory($_customTemplateDirectory);
        $this->input->setBoardEditor($boardEditor);

        $this->assertEquals($boardEditor, $this->input->boardEditor());
        $this->assertEquals($_customTemplateDirectory, $this->input->templateDirectory());
    }

    /**
     * DataProvider for UserInputTest::testCanSetAttributes.
     *
     * @return array Test values in the format [$filePath]
     */
    public function setAttributesProvider()
    {
        return [
            ["test"],
            ["myTest"],
            ["mySpecialTest"]
        ];
    }

    /**
     * Checks whether the Getopt options are set correctly.
     *
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
     * Checks whether a template can be loaded using the --edit option.
     *
     * @covers \Input\UserInput::fillBoard()
     */
    public function testCanLoadTemplate()
    {
        $this->optionsMock->expects($this->exactly(8))
                          ->method("getOption")
                          ->withConsecutive(array("edit"), array("template"), array("templatePosX"), array("templatePosY"),
                                            array("width"), array("height"), array("template"), array("invertTemplate"))
                          ->willReturn(true, "unittest", null, null, null, null, "unittest", null);
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
