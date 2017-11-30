<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
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
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new SaveTemplateOption($boardEditor);

        $this->assertEquals("save", $option->name());
        $this->assertEquals("saveTemplate", $option->callback());
        $this->assertEquals("Saves the board as a template", $option->description());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
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
                                  ->setMethods(array("saveTemplate"))
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $boardEditorMock = $this->getMockBuilder(BoardEditor::class)
                                ->setMethods(array("readInput", "templateSaver", "board"))
                                ->disableOriginalConstructor()
                                ->getMock();

        if ($boardEditorMock instanceof BoardEditor)
        {
            $option = new SaveTemplateOption($boardEditorMock);


            // No template name
            $expectedOutput = "Error: Invalid template name!\n";

            $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");
            $result = $option->saveTemplate("");
            $this->assertFalse($result);


            // Prepare the mocks
            $boardEditorMock->expects($this->exactly(4))
                ->method("templateSaver")
                ->willReturn($templateSaverMock);
            $boardEditorMock->expects($this->exactly(4))
                ->method("board")
                ->willReturn($board);

            $templateSaverMock->expects($this->exactly(4))
                ->method("saveTemplate")
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
                ->willReturn("n", "Y");

            $expectedOutput = "Warning: A template with that name already exists\. Overwrite the old file\? \(Y\|N\)\n"
                            . "Saving aborted\.\n\n";
            $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");

            $option->saveTemplate("testTemplateAbort");

            // Template name already exists -> overwrite
            $expectedOutput = "Warning: A template with that name already exists\. Overwrite the old file\? \(Y\|N\)\n"
                            . "Template successfully replaced!\n\n";
            $this->expectOutputRegex("~.*" . $expectedOutput . ".*~");

            $option->saveTemplate("testTemplateReplace");
        }
    }
}