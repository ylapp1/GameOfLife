<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\OptionHandler\BoardEditorOptionHandler;
use BoardEditor\OptionHandler\BoardEditorOptionParser;
use BoardEditor\OptionHandler\BoardEditorOptionLoader;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \BoardEditor\OptionHandler\BoardEditorOptionHandler works as expected.
 */
class BoardEditorOptionHandlerTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::__construct()
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("testing");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);

        $this->assertEquals($boardEditor, $optionHandler->parentBoardEditor());
        $this->assertEquals(new BoardEditorOptionLoader($optionHandler), $optionHandler->optionLoader());
        $this->assertEquals(new BoardEditorOptionParser($optionHandler), $optionHandler->optionParser());
        $this->assertTrue(is_array($optionHandler->options()));
        $this->assertGreaterThan(0, count($optionHandler->options()));
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::options()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::setOptions()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::optionLoader()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::setOptionLoader()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::optionParser()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::setOptionParser()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::parentBoardEditor()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::setParentBoardEditor()
     */
    public function testCanSetAttributes()
    {
        $boardEditor = new BoardEditor("test");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);

        $testBoardEditor = new BoardEditor("mytest");
        $testOptionHandler = new BoardEditorOptionHandler($testBoardEditor);
        $optionLoader = new BoardEditorOptionLoader($testOptionHandler);

        $optionParser = new BoardEditorOptionParser($optionHandler);
        $options = array("hello", "test", "my", "options");

        $optionHandler->setOptions($options);
        $optionHandler->setOptionLoader($optionLoader);
        $optionHandler->setOptionParser($optionParser);
        $optionHandler->setParentBoardEditor($testBoardEditor);

        $this->assertEquals($options, $optionHandler->options());
        $this->assertEquals($optionLoader, $optionHandler->optionLoader());
        $this->assertEquals($optionParser, $optionHandler->optionParser());
        $this->assertEquals($testBoardEditor, $optionHandler->parentBoardEditor());
    }

    /**
     * Checks whether the option handler can successfully call the option parser.
     *
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionHandler::parseInput()
     */
    public function testCanCallOptions()
    {
        $optionParserMock = $this->getMockBuilder(BoardEditorOptionParser::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();
        $options = array("hello", "option", "this", "is", "my", "personal", "test");

        $boardEditor = new BoardEditor("test");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);
        if ($optionParserMock instanceof BoardEditorOptionParser) $optionHandler->setOptionParser($optionParserMock);
        $optionHandler->setOptions($options);

        $input = "Hello, my option";

        $optionParserMock->expects($this->exactly(1))
                         ->method("callOption")
                         ->with($input, $options)
                         ->willReturn(true);

        $this->assertTrue($optionHandler->parseInput($input));
    }
}