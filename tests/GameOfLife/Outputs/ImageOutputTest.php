<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Output\ImageOutput;
use PHPUnit\Framework\TestCase;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Checks whether \Output\ImageOutput works as expected.
 */
class ImageOutputTest extends TestCase
{
    /** @var ImageOutput */
    private $output;
    private $testDirectory = __DIR__ . "/../ImageOutputTest/";
    /** @var FileSystemHandler */
    private $fileSystemHandler;

    protected function setUp()
    {
        $this->fileSystemHandler = new FileSystemHandler();
        $this->output = new ImageOutput("TEST IMAGE OUTPUT", "test", $this->testDirectory);
    }

    protected function tearDown()
    {
        try
        {
            $this->fileSystemHandler->deleteDirectory($this->testDirectory, true);
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }
        unset($this->fileSystemHandler);
        unset($this->output);
    }

    /**
     * @dataProvider constructionProvider()
     * @covers \Output\ImageOutput::__construct()
     *
     * @param string $_optionPrefix              Prefix of the image output
     * @param string $_imageOutputDirectory      Output directory of the image output
     */
    public function testCanBeConstructed(string $_optionPrefix, string $_imageOutputDirectory)
    {
        $output = new ImageOutput("TEST IMAGE OUTPUT", $_optionPrefix, $_imageOutputDirectory);

        $this->assertEquals($_optionPrefix, $output->optionPrefix());
        $this->assertNotEmpty(stristr($output->imageOutputDirectory(), $_imageOutputDirectory));
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
     * @covers \Output\ImageOutput::imageCreator()
     * @covers \Output\ImageOutput::setImageCreator()
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
        $fileSystemHandler = new FileSystemHandler();
        $colorBlack = new ImageColor(0, 0, 0);
        $imageCreator = new ImageCreator(1,2,1, $colorBlack, $colorBlack, $colorBlack);

        $this->output->setBaseOutputDirectory("hello");
        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setImageCreator($imageCreator);
        $this->output->setImageOutputDirectory($_imageOutputDirectory);
        $this->output->setOptionPrefix($_optionPrefix);

        $this->assertEquals("hello", $this->output->baseOutputDirectory());
        $this->assertEquals($fileSystemHandler, $this->output->fileSystemHandler());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
        $this->assertEquals($_imageOutputDirectory, $this->output->imageOutputDirectory());
        $this->assertEquals($_optionPrefix, $this->output->optionPrefix());
    }

    /**
     * @covers \Output\ImageOutput::startOutput()
     *
     * @throws \ReflectionException
     */
    public function testCanInitializeImageCreator()
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(4))
                    ->method("getOption")
                    ->withConsecutive(["testOutputSize"], ["testOutputCellColor"], ["testOutputBackgroundColor"], ["testOutputGridColor"])
                    ->willReturn("10", "white", "black", "red");

        $board = new Board(1,1,1,true);

        if ($optionsMock instanceof Getopt) $this->output->startOutput($optionsMock, $board);

        $imageCreator = $this->output->imageCreator();
        $baseImage = \getPrivateAttribute($imageCreator, "baseImage");

        $this->assertEquals(10, imagesx($baseImage));
        $this->assertEquals(10, imagesy($baseImage));
        $this->assertEquals(10, \getPrivateAttribute($imageCreator, "cellSize"));
    }

    /**
     * Checks whether the default values are successfully set.
     *
     * @covers \Output\ImageOutput::startOutput()
     *
     * @throws \ReflectionException
     */
    public function testCanSetDefaultValues()
    {
        $optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                            ->getMock();

        $optionsMock->expects($this->exactly(4))
                    ->method("getOption")
                    ->withConsecutive(["testOutputSize"], ["testOutputCellColor"], ["testOutputBackgroundColor"], ["testOutputGridColor"])
                    ->willReturn(null, null, null, null);

        $board = new Board(1,1,1,true);

        if ($optionsMock instanceof Getopt) $this->output->startOutput($optionsMock, $board);

        $imageCreator = $this->output->imageCreator();

        $this->assertEquals(100, \getPrivateAttribute($imageCreator, "cellSize"));
    }

    /**
     * Checks whether a new unique game id can be generated.
     *
     * @covers \Output\ImageOutput::getNewGameId()
     */
    public function testCanGetNewGameId()
    {
        $fileSystemHandlerMock = $this->getMockBuilder(\Utils\FileSystemHandler::class)
                                      ->getMock();

        if ($fileSystemHandlerMock instanceof \Utils\FileSystemHandler)
        {
            $this->output->setFileSystemHandler($fileSystemHandlerMock);
        }

        $fileSystemHandlerMock->expects($this->exactly(3))
                              ->method("getFileList")
                              ->willReturnOnConsecutiveCalls(array(), array("Game_1"), array("Game_1", "Game_2"));

        $this->assertEquals(1, $this->output->getNewGameId("PNG"));
        $this->assertEquals(2, $this->output->getNewGameId("Gif"));
        $this->assertEquals(3, $this->output->getNewGameId("Video"));
    }
}
