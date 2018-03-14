<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use OptionHandler\OptionHandler;
use PHPUnit\Framework\TestCase;
use Ulrichsg\Getopt;

/**
 * Checks whether the OptionHandler works as expected.
 */
class OptionHandlerTest extends TestCase
{
    /**
     * Checks whether the option handler can successfully initialize the option list.
     *
     * @covers \OptionHandler\OptionHandler::__construct()
     * @covers \OptionHandler\OptionHandler::excludeClasses()
     * @covers \OptionHandler\OptionHandler::linkedOptions()
     * @covers \OptionHandler\OptionHandler::optionParser()
     * @covers \OptionHandler\OptionHandler::initializeOptions()
     * @covers \OptionHandler\OptionLoader::addDefaultOptions()
     * @covers \OptionHandler\OptionLoader::addClassOptions()
     * @covers \OptionHandler\OptionLoader::getOptionsDiff()
     */
    public function testCanInitializeOptions()
    {
        $optionHandler = new OptionHandler();
        $options = new Getopt();

        $this->assertEquals(0, count($optionHandler->linkedOptions()));
        $this->assertTrue(in_array("BaseInput", $optionHandler->excludeClasses()));
        $this->assertInstanceOf(\OptionHandler\OptionParser::class, $optionHandler->optionParser());

        $optionHandler->initializeOptions($options);

        $this->assertEquals(49, count($options->getOptionList()));
        $this->assertEquals(39, count($optionHandler->linkedOptions()));
    }
}
