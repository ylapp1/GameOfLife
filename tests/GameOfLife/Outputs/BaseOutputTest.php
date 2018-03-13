<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\BaseOutput;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\BaseOutput works as expected.
 */
class BaseOutputTest extends TestCase
{
    /** @var BaseOutput */
    private $output;

    /** @var string */
    private $outputDirectory = __DIR__ . "/../BaseOutputTest/";

    protected function setUp()
    {
        $this->output = new BaseOutput();
        $this->output->setOutputDirectory($this->outputDirectory);
    }

    protected function tearDown()
    {
        unset($this->output);
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @covers \Output\BaseOutput::setOutputDirectory()
     * @covers \Output\BaseOutput::outputDirectory()
     */
    public function testCanSetAttributes()
    {
        $this->output->setOutputDirectory("Hello");
        $this->assertEquals("Hello", $this->output->outputDirectory());
    }
}
