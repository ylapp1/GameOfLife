<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\Helpers\FfmpegHelper;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\Helpers\FfmpegHelper works as expected.
 */
class FfmpegHelperTest extends TestCase
{
    /** @var FfmpegHelper */
    private $ffmpegHelper;

    protected function setUp()
    {
        $this->ffmpegHelper = new FfmpegHelper("Other");
    }

    protected function tearDown()
    {
        unset($this->ffmpegHelper);
    }


    /**
     * @covers \Output\Helpers\FfmpegHelper::__construct()
     */
    public function testCanBeConstructed()
    {
        $ffmpegHelper = new FfmpegHelper("Other");

        $this->assertEquals("", $ffmpegHelper->binaryPath());
    }

    /**
     * @dataProvider setAttributesProvider
     *
     * @covers \Output\Helpers\FfmpegHelper::setBinaryPath()
     * @covers \Output\Helpers\FfmpegHelper::binaryPath()
     * @covers \Output\Helpers\FfmpegHelper::setOptions()
     * @covers \Output\Helpers\FfmpegHelper::options()
     *
     * @param string $_binaryPath   Binary path
     * @param array $_options       Option list
     */
    public function testCanSetAttributes(string $_binaryPath, array $_options)
    {
        $this->ffmpegHelper->setBinaryPath($_binaryPath);
        $this->ffmpegHelper->setOptions($_options);

        $this->assertEquals($_binaryPath, $this->ffmpegHelper->binaryPath());
        $this->assertEquals($_options, $this->ffmpegHelper->options());
    }

    public function setAttributesProvider()
    {
        return [
            ["sdfsdf", [0, 1, 2, 3]],
            ["dflgkjsdlfgs", [1, 2, 3]],
            ["dfskjgsdklfgsdfgsd", ["he", "ll", "ow", "or", "ld"]]
        ];
    }

    /**
     * @covers \Output\Helpers\FfmpegHelper::addOption()
     * @covers \Output\Helpers\FfmpegHelper::resetOptions()
     */
    public function testCanAddOption()
    {
        $this->assertEquals(0, count($this->ffmpegHelper->options()));

        $this->ffmpegHelper->addOption("myTest");
        $this->ffmpegHelper->addOption("mySecondTest");
        $this->ffmpegHelper->addOption("myThirdTest");

        $this->assertEquals(3, count($this->ffmpegHelper->options()));
        $this->assertEquals("myTest", $this->ffmpegHelper->options()[0]);
        $this->assertEquals("mySecondTest", $this->ffmpegHelper->options()[1]);
        $this->assertEquals("myThirdTest", $this->ffmpegHelper->options()[2]);

        $this->ffmpegHelper->resetOptions();

        $this->assertEquals(0, count($this->ffmpegHelper->options()));
    }

    /**
     * @covers \Output\Helpers\FfmpegHelper::generateCommand()
     */
    public function testCanGenerateCommand()
    {
        $this->ffmpegHelper->addOption("myTest");
        $this->ffmpegHelper->addOption("thisIsATest");
        $this->ffmpegHelper->addOption("testing");

        $this->expectOutputRegex("/.*/");

        $this->assertEquals(' myTest thisIsATest testing "Output" 2>test.txt', $this->ffmpegHelper->generateCommand("Output"));
    }
}
