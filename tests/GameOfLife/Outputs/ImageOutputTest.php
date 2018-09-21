<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Simulator\Board;
use Output\JpgOutput;
use PHPUnit\Framework\TestCase;
use Ulrichsg\Getopt;
use Utils\FileSystem\FileSystemWriter;

/**
 * Checks whether \Output\ImageOutput works as expected.
 */
class ImageOutputTest extends TestCase
{
    /** @var JpgOutput */
    private $output;
    private $testDirectory = "../tests/GameOfLife/ImageOutputTest/";
    /** @var FileSystemWriter */
    private $fileSystemHandler;

    protected function setUp()
    {
        $this->fileSystemHandler = new FileSystemWriter();
        $this->output = new JpgOutput();
    }

    protected function tearDown()
    {
        try
        {
            $this->fileSystemHandler->deleteDirectory(__DIR__ . "/../../../Output/" . $this->testDirectory, true);
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }
        unset($this->fileSystemHandler);
        unset($this->output);
    }

    public function constructionProvider()
    {
        return [
            ["test", "secondtest"],
            ["atest", "mytest"],
            ["hello", "goodbye"],
            ["some/slash/es", "slash/backslash/hello"],
            ["final/test/ing", "this/is/the/final/test/for/very/long/file/paths"]
        ];
    }

    /**
     * @dataProvider constructionProvider
     * @covers \Output\ImageOutput::setBaseOutputDirectory()
     * @covers \Output\ImageOutput::baseOutputDirectory()
     * @covers \Output\ImageOutput::fileSystemHandler()
     * @covers \Output\ImageOutput::setFileSystemHandler()
     * @covers \Output\ImageOutput::imageOutputDirectory()
     * @covers \Output\ImageOutput::setImageOutputDirectory()
     * @covers \Output\ImageOutput::optionPrefix()
     * @covers \Output\ImageOutput::setOptionPrefix()
     *
     * @param string $_optionPrefix              Prefix of the image output
     * @param string $_imageOutputDirectory      Output directory of the image output
     */
    public function testCanSetAttributes($_imageOutputDirectory, $_optionPrefix)
    {
        $fileSystemHandler = new FileSystemWriter();

        $this->output->setBaseOutputDirectory("hello");
        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setImageOutputDirectory($_imageOutputDirectory);
        $this->output->setOptionPrefix($_optionPrefix);

        $this->assertEquals("hello", $this->output->baseOutputDirectory());
        $this->assertEquals($fileSystemHandler, $this->output->fileSystemHandler());
        $this->assertEquals($_imageOutputDirectory, $this->output->imageOutputDirectory());
        $this->assertEquals($_optionPrefix, $this->output->optionPrefix());
    }

    /**
     * @covers \Output\ImageOutput::startOutput()
     *
     * @throws \Exception
     */
    public function testCanInitializeImageCreator()
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(4))
                    ->method("getOption")
                    ->withConsecutive(["jpgOutputSize"], ["jpgOutputCellColor"], ["jpgOutputBackgroundColor"], ["jpgOutputGridColor"])
                    ->willReturn("10", "white", "black", "red");

        $board = new Board(1,1,true);

        // Hide output
        $this->expectOutputRegex("/.*/");
        if ($optionsMock instanceof Getopt) $this->output->startOutput($optionsMock, $board);
    }

    /**
     * Checks whether the default values are successfully set.
     *
     * @covers \Output\ImageOutput::startOutput()
     *
     * @throws \Exception
     */
    public function testCanSetDefaultValues()
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(4))
                    ->method("getOption")
                    ->withConsecutive(["jpgOutputSize"], ["jpgOutputCellColor"], ["jpgOutputBackgroundColor"], ["jpgOutputGridColor"])
                    ->willReturn(null, null, null, null);

        $board = new Board(1,1,true);

        // Hide output
        $this->expectOutputRegex("/.*/");
        if ($optionsMock instanceof Getopt) $this->output->startOutput($optionsMock, $board);
    }

    /**
     * Checks whether a new unique game id can be generated.
     *
     * @covers \Output\ImageOutput::getNewGameId()
     *
     * @throws \Exception
     */
    public function testCanGetNewGameId()
    {
        $fileSystemHandlerMock = $this->getMockBuilder(\Utils\FileSystem\FileSystemReader::class)
                                      ->getMock();

        if ($fileSystemHandlerMock instanceof \Utils\FileSystem\FileSystemReader)
        {
            setPrivateAttribute($this->output, "fileSystemReader", $fileSystemHandlerMock);
        }

        $fileSystemHandlerMock->expects($this->exactly(3))
                              ->method("getFileList")
                              ->willReturnOnConsecutiveCalls(array(), array("Game_1"), array("Game_1", "Game_2"));

        $this->assertEquals(1, $this->output->getNewGameId("PNG"));
        $this->assertEquals(2, $this->output->getNewGameId("Gif"));
        $this->assertEquals(3, $this->output->getNewGameId("Video"));
    }
}
