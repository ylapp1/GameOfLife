<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\OptionHandler\BoardEditorOptionHandler;
use BoardEditor\OptionHandler\BoardEditorOptionParser;
use BoardEditor\Options\StartOption;
use BoardEditor\Options\ExitOption;
use BoardEditor\Options\ToggleFieldOption;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \BoardEditor\OptionHandler\BoardEditorOptionParser works as expected.
 */
class BoardEditorOptionParserTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionParser::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("tests");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);

        $optionParser = new BoardEditorOptionParser($optionHandler);
        $this->assertEquals($optionHandler, $optionParser->parentOptionHandler());
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @dataProvider setAttributesProvider()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionParser::parentOptionHandler()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionParser::setParentOptionHandler()
     *
     * @param String $_templateDirectory Template directory
     *
     * @throws \Exception
     */
    public function testCanSetAttributes(String $_templateDirectory)
    {
        $boardEditor = new BoardEditor("tests");
        $optionHandler = new BoardEditorOptionHandler($boardEditor);
        $optionParser = new BoardEditorOptionParser($optionHandler);

        $testBoardEditor = new BoardEditor($_templateDirectory);
        $testOptionHandler = new BoardEditorOptionHandler($testBoardEditor);

        $optionParser->setParentOptionHandler($testOptionHandler);
        $this->assertEquals($testOptionHandler, $optionParser->parentOptionHandler());
    }

    /**
     * DataProvider for BoardEditorOptionParserTest::setAttributesProvider().
     *
     * @return array Test values
     */
    public function setAttributesProvider()
    {
        return array(
            array("hello"),
            array("myTest"),
            array("helloTest"),
            array("finalTest")
        );
    }

    /**
     * Checks whether available options can be successfully called.
     *
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionParser::callOption()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionParser::isOption()
     * @covers \BoardEditor\OptionHandler\BoardEditorOptionParser::splitOption()
     *
     * @throws \Exception
     */
    public function testCanCallOption()
    {
        $optionHandlerMock = $this->getMockBuilder(BoardEditorOptionHandler::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();
        if ($optionHandlerMock instanceof BoardEditorOptionHandler)
        {
            $optionParser = new BoardEditorOptionParser($optionHandlerMock);

            $startOptionMock = $this->getMockBuilder(StartOption::class)
                                    ->setMethods(array("callback", "start", "getNumberOfArguments", "hasAlias"))
                                    ->disableOriginalConstructor()
                                    ->getMock();
            $exitOptionMock = $this->getMockBuilder(ExitOption::class)
                                   ->setMethods(array("hasAlias", "name", "getNumberOfArguments"))
                                   ->disableOriginalConstructor()
                                   ->getMock();
            $toggleOptionMock = $this->getMockBuilder(ToggleFieldOption::class)
                                     ->setMethods(array("hasAlias", "getNumberOfArguments"))
                                     ->disableOriginalConstructor()
                                     ->getMock();

            $optionList = array(
                "start" => $startOptionMock,
                "exit" => $exitOptionMock,
                "toggle" => $toggleOptionMock
            );


            // 2 x 2 calls, 1 x 1 call, 1 x 2 calls
            $optionHandlerMock->expects($this->exactly(7))
                ->method("options")
                ->willReturn($optionList);


            // Valid option with main name, valid amount of arguments
            $startOptionMock->expects($this->exactly(1))
                            ->method("getNumberOfArguments")
                            ->willReturn(0);
            $startOptionMock->expects($this->exactly(1))
                            ->method("callback")
                            ->willReturn("start");
            $startOptionMock->expects($this->exactly(1))
                            ->method("start")
                            ->willReturn(true);

            $result = $optionParser->callOption("start");
            $this->assertTrue($result);


            // Options with alias look ups
            $startOptionMock->expects($this->exactly(2))
                            ->method("hasAlias")
                            ->withConsecutive(array("quit"), array("random"))
                            ->willReturn(false);
            $exitOptionMock->expects($this->exactly(2))
                           ->method("hasAlias")
                           ->withConsecutive(array("quit"), array("random"))
                           ->willReturn(true, false);


            // Valid option with alias, invalid amount of arguments
            $exitOptionMock->expects($this->exactly(2))
                           ->method("getNumberOfArguments")
                           ->willReturn(1);

            $exceptionOccurred = false;
            try
            {
                $optionParser->callOption("quit");
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("Invalid number of arguments (Expected 1, Got 0).", $_exception->getMessage());
            }
            $this->assertTrue($exceptionOccurred);


            // Invalid option
            $exceptionOccurred = false;
            try
            {
                $optionParser->callOption("random");
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("Invalid option \"random\".", $_exception->getMessage());
            }
            $this->assertTrue($exceptionOccurred);


            // Alias for toggle
            $toggleOptionMock->expects($this->exactly(2))
                             ->method("getNumberOfArguments")
                             ->willReturn(3);

            $exceptionOccurred = false;
            try
            {
                $optionParser->callOption("1,2");
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("Invalid number of arguments (Expected 3, Got 2).", $_exception->getMessage());
            }
            $this->assertTrue($exceptionOccurred);
        }
    }
}
