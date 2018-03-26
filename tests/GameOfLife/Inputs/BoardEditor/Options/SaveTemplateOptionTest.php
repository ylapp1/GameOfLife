<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\SaveTemplateOption;
use GameOfLife\Board;
use TemplateHandler\TemplateSaver;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the BoardEditor option "save" works as expected.
 */
class SaveTemplateOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\SaveTemplateOption::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $fakeTemplateDirectory = "test";

        $boardEditor = new BoardEditor($fakeTemplateDirectory);
        $option = new SaveTemplateOption($boardEditor);
        $templateSaver = new TemplateSaver($fakeTemplateDirectory);

        $this->assertEquals("save", $option->name());
        $this->assertEquals("saveTemplate", $option->callback());
        $this->assertEquals("Saves the board as a template", $option->description());
        $this->assertEquals(1, $option->getNumberOfArguments());
        $this->assertEquals($templateSaver, $option->templateSaver());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @dataProvider setAttributesProvider()
     * @covers \BoardEditor\Options\SaveTemplateOption::templateSaver()
     * @covers \BoardEditor\Options\SaveTemplateOption::setTemplateSaver()
     *
     * @param String $_templateDirectory
     *
     * @throws \Exception
     */
    public function testCanSetAttributes(String $_templateDirectory)
    {
        $templateSaver = new TemplateSaver($_templateDirectory);

        $boardEditor = new BoardEditor("hello");
        $option = new SaveTemplateOption($boardEditor);

        $option->setTemplateSaver($templateSaver);
        $this->assertEquals($templateSaver, $option->templateSaver());
    }

    /**
     * DataProvider for SaveTemplateOptionTest::testCanSetAttributes.
     *
     * @return array Test values
     */
    public function setAttributesProvider()
    {
        return array(
            array("MyTest"),
            array("NotMyTest"),
            array("This/is/a/test"),
            array("WelcomeToMy/Test")
        );
    }


    /**
     * Checks whether the currently edited board can be saved to a file.
     *
     * @covers \BoardEditor\Options\SaveTemplateOption::saveTemplate()
     */
    public function testCanSaveCustomTemplate()
    {
        $board = new Board(2, 2, 1, true);
        $board->setField(1, 1, true);
        $this->assertEquals(1, $board->getAmountCellsAlive());

        $templateSaverMock = $this->getMockBuilder(TemplateSaver::class)
                                  ->setMethods(array("saveCustomTemplate"))
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $boardEditorMock = $this->getMockBuilder(BoardEditor::class)
                                ->setMethods(array("readInput", "templateSaver", "board", "templateDirectory"))
                                ->disableOriginalConstructor()
                                ->getMock();

        if ($boardEditorMock instanceof BoardEditor)
        {
            $boardEditorMock->expects($this->exactly(1))
                            ->method("templateDirectory")
                            ->willReturn("hello");

            $option = new SaveTemplateOption($boardEditorMock);
            if ($templateSaverMock instanceof TemplateSaver) $option->setTemplateSaver($templateSaverMock);


            // No template name
            $expectedOutput = "Error: Invalid template name!\n";

            $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");
            $result = $option->saveTemplate("");
            $this->assertFalse($result);


            // Prepare the mocks
            $boardEditorMock->expects($this->exactly(4))
                ->method("board")
                ->willReturn($board);

            $templateSaverMock->expects($this->exactly(4))
                ->method("saveCustomTemplate")
                ->withConsecutive(array("testTemplate", $board),
                    array("testTemplateAbort", $board),
                    array("testTemplateReplace", $board),
                    array("testTemplateReplace", $board))
                ->willReturn(true, false, false, true);

            // Template saved successfully
            $expectedOutput = "Template successfully saved!\n\n"
                . 'You can set/unset more cells or start the simulation by typing "start"\n\n';
            $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");

            $option->saveTemplate("testTemplate");


            // Template name already exists -> abort
            $boardEditorMock->expects($this->exactly(2))
                ->method("readInput")
                ->withConsecutive(array("Overwrite the old file? (Yes|No): "), array("Overwrite the old file? (Yes|No): "))
                ->willReturn("n", "Y");

            $expectedOutput = "Warning: A template with that name already exists\.\n"
                            . "Saving aborted\.\n\n";
            $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");

            $option->saveTemplate("testTemplateAbort");

            // Template name already exists -> overwrite
            $expectedOutput = "Warning: A template with that name already exists\.\n"
                            . "Template successfully replaced!\n\n";
            $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");

            $option->saveTemplate("testTemplateReplace");
        }
    }
}
