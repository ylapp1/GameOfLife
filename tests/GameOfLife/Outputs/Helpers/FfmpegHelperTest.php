<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\Helpers\FfmpegHelper;
use PHPUnit\Framework\TestCase;
use Utils\FileSystemHandler;
use Utils\ShellExecutor;

/**
 * Checks whether \Output\Helpers\FfmpegHelper works as expected.
 */
class FfmpegHelperTest extends TestCase
{
    /** @var FfmpegHelper */
    private $ffmpegHelper;

    /**
     * @throws Exception
     */
    protected function setUp()
    {
        $this->ffmpegHelper = new FfmpegHelper("Other");
    }

    protected function tearDown()
    {
        unset($this->ffmpegHelper);
    }


    /**
     * Checks whether the constructor works as expected.
     *
     * @throws \Exception
     *
     * @covers \Output\Helpers\FfmpegHelper::__construct()
     * @covers \Output\Helpers\FfmpegHelper::findFFmpegBinary()
     */
    public function testCanBeConstructed()
    {
        $ffmpegHelper = new FfmpegHelper("Other");
        $this->assertEquals("", $ffmpegHelper->binaryPath());
    }

    /**
     * Checks whether the ffmpeg helper can find the ffmpeg binary for windows.
     *
     * @throws \Exception
     *
     * @covers \Output\Helpers\FfmpegHelper::findFFmpegBinary()
     */
    public function testCanFindFfmpegBinaryForWindows()
    {
        $ffmpegHelper = new FfmpegHelper("test");

        $reflectionClass = new ReflectionClass(\Output\Helpers\FfmpegHelper::class);

        $fileSystemHandlerMock = $this->getMockBuilder(\Utils\FileSystemHandler::class)
                                      ->getMock();

        $fileSystemHandlerMock->expects($this->exactly(1))
                              ->method("findFileRecursive")
                              ->willReturn("ffmpeg.exe");

        $reflectionProperty = $reflectionClass->getProperty("fileSystemHandler");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($ffmpegHelper, $fileSystemHandlerMock);

        $reflectionProperty = $reflectionClass->getProperty("osName");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($ffmpegHelper, "Windows");

        $reflectionMethod = $reflectionClass->getMethod("findFfmpegBinary");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($ffmpegHelper);

        $this->assertEquals("ffmpeg.exe", $result);
    }

    /**
     * Checks whether the ffmpeg helper can find the ffmpeg binary for linux.
     *
     * @throws \Exception
     *
     * @covers \Output\Helpers\FfmpegHelper::findFFmpegBinary()
     */
    public function testCanFindFfmpegBinaryForLinux()
    {
        $ffmpegHelper = new FfmpegHelper("test");

        $reflectionClass = new ReflectionClass(\Output\Helpers\FfmpegHelper::class);

        $shellExecutorMock = $this->getMockBuilder(\Utils\ShellExecutor::class)
                                  ->setConstructorArgs(array(PHP_OS))
                                  ->getMock();

        $shellExecutorMock->expects($this->exactly(1))
                          ->method("executeCommand")
                          ->willReturn(1);

        $reflectionProperty = $reflectionClass->getProperty("shellExecutor");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($ffmpegHelper, $shellExecutorMock);

        $reflectionProperty = $reflectionClass->getProperty("osName");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($ffmpegHelper, "Linux");

        $reflectionMethod = $reflectionClass->getMethod("findFfmpegBinary");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($ffmpegHelper);

        $this->assertEquals("ffmpeg", $result);
    }

    /**
     * Checks whether the getters and setters work as expected.
     *
     * @dataProvider setAttributesProvider
     *
     * @covers \Output\Helpers\FfmpegHelper::setBinaryPath()
     * @covers \Output\Helpers\FfmpegHelper::binaryPath()
     * @covers \Output\Helpers\FfmpegHelper::setOptions()
     * @covers \Output\Helpers\FfmpegHelper::options()
     * @covers \Output\Helpers\FfmpegHelper::shellExecutor()
     * @covers \Output\Helpers\FfmpegHelper::setShellExecutor()
     * @covers \Output\Helpers\FfmpegHelper::fileSystemHandler()
     * @covers \Output\Helpers\FfmpegHelper::setFileSystemHandler()
     *
     * @param string $_binaryPath The binary path
     * @param array $_options The option list
     */
    public function testCanSetAttributes(string $_binaryPath, array $_options)
    {
        $shellExecutor = new ShellExecutor("test");
        $fileSystemHandler = new FileSystemHandler();

        $this->ffmpegHelper->setBinaryPath($_binaryPath);
        $this->ffmpegHelper->setOptions($_options);
        $this->ffmpegHelper->setShellExecutor($shellExecutor);
        $this->ffmpegHelper->setFileSystemHandler($fileSystemHandler);

        $this->assertEquals($_binaryPath, $this->ffmpegHelper->binaryPath());
        $this->assertEquals($_options, $this->ffmpegHelper->options());
        $this->assertEquals($shellExecutor, $this->ffmpegHelper->shellExecutor());
        $this->assertEquals($fileSystemHandler, $this->ffmpegHelper->fileSystemHandler());
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

        $this->assertEquals(' myTest thisIsATest testing "Output"', $this->ffmpegHelper->generateCommand("Output"));
    }

    /**
     * Checks whether an command can be successfully executed.
     *
     * @param int $_shellExecutorReturnValue
     *
     * @throws \Exception
     *
     * @dataProvider executeCommandProvider()
     *
     * @covers \Output\Helpers\FfmpegHelper::executeCommand()
     */
    public function testCanExecuteCommand(int $_shellExecutorReturnValue)
    {
        $shellExecutorMock = $this->getMockBuilder(\Utils\ShellExecutor::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $shellExecutorMock->expects($this->exactly(1))
                          ->method("executeCommand")
                          ->willReturn($_shellExecutorReturnValue);

        if ($shellExecutorMock instanceof \Utils\ShellExecutor)
        {
            $this->ffmpegHelper->setShellExecutor($shellExecutorMock);

            $exceptionOccurred = false;
            try
            {
                $this->ffmpegHelper->executeCommand("Hello");
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals("Ffmpeg returned the error code \"" . $_shellExecutorReturnValue . "\".", $_exception->getMessage());
            }

            if ($_shellExecutorReturnValue) $this->assertTrue($exceptionOccurred);
            else $this->assertFalse($exceptionOccurred);
        }
    }

    /**
     * DataProvider for FfmpegHelperTest::testCanExecuteCommand().
     *
     * @return array Test values in the format array(int shellExecutorReturnValue)
     */
    public function executeCommandProvider(): array
    {
        return array(
            array(5),
            array(3),
            array(1),
            array(0),
            array(7),
            array(10)
        );
    }
}
