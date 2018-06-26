<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\Helpers\FfmpegHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Utils\OsInformationFetcher;
use Utils\Shell\ShellInformationFetcher;

/**
 * Checks whether \Output\Helpers\FfmpegHelper works as expected.
 */
class FfmpegHelperTest extends TestCase
{
    /** @var FfmpegHelper */
    private $ffmpegHelper;

    /**
     * @var MockObject $osInformationFetcherMock
     */
    private $osInformationFetcherMock;

    /**
     * @throws Exception
     */
    protected function setUp()
    {
        // Create a new ffmpeg helper and set its os type to linux
        $this->ffmpegHelper = new FfmpegHelper();
        $this->osInformationFetcherMock = $this->getMockBuilder(OsInformationFetcher::class)
                                               ->getMock();
        setPrivateAttribute($this->ffmpegHelper, "osInformationFetcher", $this->osInformationFetcherMock);
    }

    protected function tearDown()
    {
        unset($this->ffmpegHelper);
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
        $fileSystemHandlerMock = $this->getMockBuilder(\Utils\FileSystem\FileSystemReader::class)
                                      ->getMock();
        $fileSystemHandlerMock->expects($this->exactly(1))
                              ->method("findFileRecursive")
                              ->willReturn("ffmpeg.exe");

        setPrivateAttribute($this->ffmpegHelper, "fileSystemReader", $fileSystemHandlerMock);

        $this->osInformationFetcherMock->expects($this->exactly(1))
                                       ->method("isWindows")
                                       ->willReturn(true);

        $reflectionClass = new ReflectionClass(\Output\Helpers\FfmpegHelper::class);
        $reflectionMethod = $reflectionClass->getMethod("findFfmpegBinary");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->ffmpegHelper);

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
        $shellExecutorMock = $this->getMockBuilder(\Utils\Shell\ShellExecutor::class)
                                  ->getMock();
        $shellExecutorMock->expects($this->exactly(1))
                          ->method("executeCommand")
                          ->willReturn(1);

        $this->osInformationFetcherMock->expects($this->once())
                                       ->method("isWIndows")
                                       ->willReturn(false);

        $this->osInformationFetcherMock->expects($this->exactly(1))
                                       ->method("isLinux")
                                       ->willReturn(true);

        setPrivateAttribute($this->ffmpegHelper, "shellExecutor", $shellExecutorMock);

        $reflectionClass = new ReflectionClass(\Output\Helpers\FfmpegHelper::class);
        $reflectionMethod = $reflectionClass->getMethod("findFfmpegBinary");
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->ffmpegHelper);

        $this->assertEquals("ffmpeg", $result);
    }

    /**
     * @covers \Output\Helpers\FfmpegHelper::addOption()
     * @covers \Output\Helpers\FfmpegHelper::resetOptions()
     *
     * @throws ReflectionException
     */
    public function testCanAddOption()
    {
        $ffmpegOptions = getPrivateAttribute($this->ffmpegHelper, "options");
        $this->assertEquals(0, count($ffmpegOptions));

        $this->ffmpegHelper->addOption("myTest");
        $this->ffmpegHelper->addOption("mySecondTest");
        $this->ffmpegHelper->addOption("myThirdTest");

        $ffmpegOptions = getPrivateAttribute($this->ffmpegHelper, "options");
        $this->assertEquals(3, count($ffmpegOptions));
        $this->assertEquals("myTest", $ffmpegOptions[0]);
        $this->assertEquals("mySecondTest", $ffmpegOptions[1]);
        $this->assertEquals("myThirdTest", $ffmpegOptions[2]);

        $this->ffmpegHelper->resetOptions();

        $ffmpegOptions = getPrivateAttribute($this->ffmpegHelper, "options");
        $this->assertEquals(0, count($ffmpegOptions));
    }

    /**
     * @covers \Output\Helpers\FfmpegHelper::generateCommand()
     *
     * @throws \Exception
     */
    public function testCanGenerateCommand()
    {

        setPrivateAttribute($this->ffmpegHelper, "binaryPath", "ffmpeg");

        $this->osInformationFetcherMock->expects($this->exactly(2))
                                       ->method("isWindows")
                                       ->willReturn(false);

        $this->ffmpegHelper->addOption("myTest");
        $this->ffmpegHelper->addOption("thisIsATest");
        $this->ffmpegHelper->addOption("testing");

        // Hide output
        $this->expectOutputRegex("/.*/");
        $this->assertEquals('ffmpeg myTest thisIsATest testing "Output"', $this->ffmpegHelper->generateCommand("Output"));
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
        $shellExecutorMock = $this->getMockBuilder(\Utils\Shell\ShellExecutor::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $shellExecutorMock->expects($this->exactly(1))
                          ->method("executeCommand")
                          ->willReturn($_shellExecutorReturnValue);

        if ($shellExecutorMock instanceof \Utils\Shell\ShellExecutor)
        {
            setPrivateAttribute($this->ffmpegHelper, "shellExecutor", $shellExecutorMock);

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
