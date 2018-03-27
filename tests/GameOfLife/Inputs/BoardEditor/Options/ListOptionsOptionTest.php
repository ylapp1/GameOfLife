<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;
use BoardEditor\OptionHandler\BoardEditorOptionHandler;
use BoardEditor\Options\ListOptionsOption;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Options option works as expected.
 */
class ListOptionsOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\ListOptionsOption::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new ListOptionsOption($boardEditor);

        $this->assertEquals("options", $option->name());
        $this->assertEquals("listOptions", $option->callback());
        $this->assertEquals("Lists available options", $option->description());
        $this->assertEquals(0, $option->getNumberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\ListOptionsOption::listOptions()
     */
    public function testCanListOptions()
    {
        $boardEditorMock = $this->getMockBuilder(BoardEditor::class)
                                ->setMethods(array("optionHandler"))
                                ->disableOriginalConstructor()
                                ->getMock();

        $optionHandlerMock = $this->getMockBuilder(BoardEditorOptionHandler::class)
                                  ->setMethods(array("options"))
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $boardEditorOptionMock = $this->getMockBuilder(BoardEditorOption::class)
                                       ->setMethods(array("arguments", "description"))
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $testOption = clone $boardEditorOptionMock;
        $testOption->expects($this->exactly(1))
                   ->method("arguments")
                   ->willReturn(array());
        $testOption->expects($this->exactly(1))
                   ->method("description")
                   ->willReturn("Tests units");

        $jumpOption = clone $boardEditorOptionMock;
        $jumpOption->expects($this->exactly(1))
                   ->method("arguments")
                   ->willReturn(array("high jump" => "Bool"));
        $jumpOption->expects($this->exactly(1))
                   ->method("description")
                   ->willReturn("Makes the board jump");

        $flyOption = clone $boardEditorOptionMock;
        $flyOption->expects($this->exactly(1))
                  ->method("arguments")
                  ->willReturn(array());
        $flyOption->expects($this->exactly(1))
                  ->method("description")
                  ->willReturn("Makes the board fly");

        // Generate some fake options
        $boardEditorOptions = array(
            "test" => $testOption,
            "jumpVeryLongTestOption" => $jumpOption,
            "flyShort" => $flyOption
        );

        if ($boardEditorMock instanceof BoardEditor)
        {
            $listOptionsOption = new ListOptionsOption($boardEditorMock);

            $boardEditorMock->expects($this->exactly(1))
                            ->method("optionHandler")
                            ->willReturn($optionHandlerMock);

            $optionHandlerMock->expects($this->exactly(1))
                              ->method("options")
                              ->willReturn($boardEditorOptions);

            $expectedOutput = "\n\nOptions:"
                            . "\n - test                               : Tests units"
                            . "\n - jumpVeryLongTestOption <high jump> : Makes the board jump"
                            . "\n - flyShort                           : Makes the board fly"
                            . "\n\n";

            $this->expectOutputString($expectedOutput);

            $result = $listOptionsOption->listOptions();
            $this->assertFalse($result);
        }
    }
}
