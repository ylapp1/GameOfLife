<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use OptionHandler\OptionHandler;
use OptionHandler\OptionParser;
use PHPUnit\Framework\TestCase;
use Ulrichsg\Getopt;

/**
 * Checks whether the option parser works as expected.
 */
class OptionParserTest extends TestCase
{
    /**
     * The options list mock
     *
     * @var \PHPUnit\Framework\MockObject\MockObject $optionsMock
     */
    private $optionsMock;

    /**
     * The option parser
     *
     * @var OptionParser $optionParser
     */
    private $optionParser;

    /**
     * The parent option handler mock
     *
     * @var \PHPUnit\Framework\MockObject\MockObject $parentOptionHandlerMock
     */
    private $parentOptionHandlerMock;


    /**
     * Function that is called before each test.
     */
    protected function setUp()
    {
        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();

        $this->parentOptionHandlerMock = $this->getMockBuilder(\OptionHandler\Optionhandler::class)
                                              ->getMock();

        if ($this->parentOptionHandlerMock instanceof OptionHandler)
        {
            $this->optionParser = new OptionParser($this->parentOptionHandlerMock);
        }
    }

    /**
     * Function that is called after each test.
     */
    protected function tearDown()
    {
        unset($this->optionsMock);
        unset($this->optionhandlerMock);
        unset($this->optionParser);
        unset($this->parentOptionHandlerMock);
    }


    /**
     * Checks whether the optionParser can parse general options.
     *
     * @covers \OptionHandler\OptionParser::__construct()
     * @covers \OptionHandler\OptionParser::parseGeneralOptions()
     */
    public function testCanParseGeneralOptions()
    {
        $this->optionsMock->expects($this->exactly(5))
                          ->method("getOption")
                          ->withConsecutive(array("version"), array("version"), array("help"), array("version"), array("help"))
                          ->willReturn(true, null, true, null, null);

        if ($this->optionsMock instanceof Getopt)
        {
            // Hide output
            $this->expectOutputRegex("/.*/");
            $generalOptionCalled = $this->optionParser->parseGeneralOptions($this->optionsMock);
            $this->assertTrue($generalOptionCalled);

            $generalOptionCalled = $this->optionParser->parseGeneralOptions($this->optionsMock);
            $this->assertTrue($generalOptionCalled);

            $generalOptionCalled = $this->optionParser->parseGeneralOptions($this->optionsMock);
            $this->assertFalse($generalOptionCalled);
        }
    }

    /**
     * Checks whether the option parser can parse board options.
     *
     * @param array $_returnValueMaps The return values for "getOption" in the format array("optionName", "returnValue")
     * @param Board|Bool $_expectedBoard The expected Board object or false
     *
     * @throws \Exception
     *
     * @dataProvider parseBoardOptionsProvider
     * @covers \OptionHandler\OptionParser::parseBoardOptions()
     */
    public function testCanParseBoardOptions(array $_returnValueMaps, $_expectedBoard = null)
    {
        $this->optionsMock->expects($this->exactly(count($_returnValueMaps)))
            ->method("getOption")
            ->willReturnMap($_returnValueMaps);


        if ($this->optionsMock instanceof Getopt)
        {
            $exceptionOccurred = false;
            $board = new Board(0,0,true);
            try
            {
                $board = $this->optionParser->parseBoardOptions($this->optionsMock);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("Invalid border type specified.", $_exception->getMessage());
            }

            if (! $_expectedBoard) $this->assertTrue($exceptionOccurred);
            else
            {
                $this->assertFalse($exceptionOccurred);
                $this->assertEquals($_expectedBoard, $board);
            }
        }
    }

    /**
     * DataProvider for OptionParserTest::testCanParseBoardOptions().
     *
     * @return array Test values in the format array(getopt values, expected Board object)
     */
    public function parseBoardOptionsProvider(): array
    {
        return array(
            "Custom values with border" => array(
                array(
                    array("width", "1"),
                    array("width", "1"),
                    array("height", "2"),
                    array("height", "2"),
                    array("border", "solid"),
                    array("border", "solid")
                ),
                new Board(1, 2, true)
            ),
            "Custom values without border" => array(
                array(
                    array("width", "5"),
                    array("width", "5"),
                    array("height", "18"),
                    array("height", "18"),
                    array("border", "passthrough"),
                    array("border", "passthrough")
                ),
                new Board(5, 18, false)
            ),
            "Custom values with invalid border type" => array(
                array(
                    array("width", "19"),
                    array("width", "19"),
                    array("height", "13"),
                    array("height", "13"),
                    array("border", "glass"),
                    array("border", "glass")
                ),
                false
            ),
            "Default values" => array(
                array(
                    array("width", null),
                    array("height", null),
                    array("border", null)
                ),
                new Board(20, 10, true)
            )
        );
    }


    /**
     * Checks whether input options are parsed correctly.
     *
     * @param String $_optionParseFunction The function that will be tested
     * @param array $_returnValueMaps The return values for "getOption" in the format array("optionName", "returnValue")
     * @param String $_expectedClass The expected input class that is returned from parsing the input options
     * @param String[] $_excludeClasses The excluded classes
     * @param array $_linkedOptions The linked options array
     *
     * @dataProvider parseClassOptionsProvider()
     * @covers \OptionHandler\OptionParser::parseInputOptions()
     * @covers \OptionHandler\OptionParser::parseOutputOptions()
     * @covers \OptionHandler\OptionParser::parseRuleOptions()
     * @covers \OptionHandler\OptionParser::parseClassOptions()
     */
    public function testCanParseClassOptions(String $_optionParseFunction, array $_returnValueMaps, String $_expectedClass, array $_excludeClasses, array $_linkedOptions = array())
    {
        $this->optionsMock->expects($this->exactly(count($_returnValueMaps)))
                          ->method("getOption")
                          ->willReturnmap($_returnValueMaps);

        if (count($_excludeClasses) > 0)
        {
            $this->parentOptionHandlerMock->expects($this->exactly(1))
                                          ->method("excludeClasses")
                                          ->willReturn($_excludeClasses);
        }

        $this->parentOptionHandlerMock->expects($this->atMost(1))
                                      ->method("linkedOptions")
                                      ->willReturn($_linkedOptions);

        if ($this->optionsMock instanceof Getopt)
        {
            $input = $this->optionParser->$_optionParseFunction($this->optionsMock);
            $this->assertInstanceOf($_expectedClass, $input);
        }
    }

    /**
     * DataProvider for OptionParserTest::testCanParseClassOptions().
     *
     * @return array Test values in the format array(getOption values, expectedClass, classExists, linkedOptions)
     */
    public function parseClassOptionsProvider(): array
    {
        return array(

            // Input
            "input = Valid class name (User)" => array(
                "parseInputOptions",
                array(array("input", "user"), array("input", "user")),
                \Input\UserInput::class,
                array("BaseInput")
            ),
            "input = Excluded class name (Base)" => array(
                "parseInputOptions",
                array(array("input", "Base"), array("input", "Base")),
                \Input\TemplateInput::class,
                array("BaseInput")
            ),
            "input = Invalid class name (HelloWorld)" => array(
                "parseInputOptions",
                array(array("input", "HelloWorld"), array("input", "HelloWorld")),
                \Input\TemplateInput::class,
                array(),
            ),
            "input = test, template = true" => array(
                "parseInputOptions",
                array(array("input", "test"), array("input", "test"), array("template", true)),
                \Input\TemplateInput::class,
                array(),
                array("template" => "\Input\TemplateInput")
            ),

            // Output
            "output = Valid class name (Gif)" => array(
                "parseOutputOptions",
                array(array("output", "Gif"), array("output", "Gif")),
                \Output\GifOutput::class,
                array("BaseOutput")
            ),
            "output = Excluded class name (Base)" => array(
                "parseOutputOptions",
                array(array("output", "Base"), array("output", "Base")),
                \Output\ConsoleOutput::class,
                array("BaseOutput")
            ),
            "output = Invalid class name (HelloWorld)" => array(
                "parseOutputOptions",
                array(array("output", "HelloWorld"), array("output", "HelloWorld")),
                \Output\ConsoleOutput::class,
                array()
            ),
            "output = test, videoOutputAddSound = true" => array(
                "parseOutputOptions",
                array(array("output", "test"), array("output", "test"), array("videoOutputAddSound", true)),
                \Output\VideoOutput::class,
                array(),
                array("videoOutputAddSound" => "\Output\VideoOutput")
            ),

            // Rule
            "rules = Valid class name (Copy)" => array(
                "parseRuleOptions",
                array(array("rules", "Copy"), array("rules", "Copy")),
                \Rule\CopyRule::class,
                array("BaseRule")
            ),
            "rules = Excluded class name (Base)" => array(
                "parseRuleOptions",
                array(array("rules", "Base"), array("rules", "Base")),
                \Rule\ConwayRule::class,
                array("BaseRule")
            ),
            "rules = Invalid class name (HelloWorld)" => array(
                "parseRuleOptions",
                array(array("rules", "HelloWorld"), array("rules", "HelloWorld")),
                \Rule\ConwayRule::class,
                array()
            ),
            "rules = test, testRuleOption = true" => array(
                "parseRuleOptions",
                array(array("rules", "test"), array("rules", "test"), array("testRuleOption", true)),
                \Rule\Two45Rule::class,
                array(),
                array("testRuleOption" => "\Rule\Two45Rule")
            )
        );
    }
}
