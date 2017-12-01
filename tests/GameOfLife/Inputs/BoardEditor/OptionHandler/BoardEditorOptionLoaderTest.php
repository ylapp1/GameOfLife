<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\OptionHandler\BoardEditorOptionHandler;
use BoardEditor\OptionHandler\BoardEditorOptionLoader;
use BoardEditor\Options\StartOption;
use BoardEditor\Options\ExitOption;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \BoardEditor\OptionHandler\BoardEditorOptionLoader works as expected
 */
class BoardEditorOptionLoaderTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionLoader::__construct()
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("mytest");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);

        $optionLoader = new BoardEditorOptionLoader($optionHandler);

        $this->assertEquals(new FileSystemHandler(), $optionLoader->fileSystemHandler());
        $this->assertEquals($optionHandler, $optionLoader->parentOptionHandler());
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @dataProvider setAttributesProvider()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionLoader::fileSystemHandler()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionLoader::setFileSystemHandler()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionLoader::parentOptionHandler()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionLoader::setParentOptionHandler()
     *
     * @param String $_templateDirectory Template directory
     */
    public function testCanSetAttributes(String $_templateDirectory)
    {
        $fileSystemHandler = new FileSystemHandler();

        $boardEditor = new BoardEditor("test");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);

        $testBoardEditor = new BoardEditor($_templateDirectory);
        $testOptionHandler = new BoardEditorOptionHandler($testBoardEditor);

        $optionLoader = new BoardEditorOptionLoader($optionHandler);

        $optionLoader->setFileSystemHandler(($fileSystemHandler));
        $optionLoader->setParentOptionHandler($testOptionHandler);

        $this->assertEquals($fileSystemHandler, $optionLoader->fileSystemHandler());
        $this->assertEquals($testOptionHandler, $optionLoader->parentOptionHandler());
    }

    /**
     * DataProvider for BoardEditorOptionLoaderTest::testCanSetAttributes.
     *
     * @return array Test values
     */
    public function setAttributesProvider()
    {
        return array(
            array("hello"),
            array("mytest"),
            array("attribute"),
            array("testing")
        );
    }

    /**
     * Checks whether options can be successfully loaded.
     *
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionLoader::loadOptions()
     */
    public function testCanLoadOptions()
    {
        $boardEditor = new BoardEditor("test");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);
        $optionLoader = new BoardEditorOptionLoader($optionHandler);

        $fileSystemHandlerMock = $this->getMockBuilder(FileSystemHandler::class)
                                      ->getMock();
        if ($fileSystemHandlerMock instanceof FileSystemHandler) $optionLoader->setFileSystemHandler($fileSystemHandlerMock);

        $templateDirectory = "testing";
        $fileSystemHandlerMock->expects($this->exactly(1))
            ->method("getFileList")
            ->with($templateDirectory, "Option.php")
            ->willReturn(array("ExitOption", "StartOption"));

        $result = $optionLoader->loadOptions($templateDirectory);

        $expectedResult = array(
            "exit" =>  new ExitOption($boardEditor),
            "start" =>new StartOption($boardEditor)
        );

        $this->assertEquals($expectedResult, $result);
    }
}