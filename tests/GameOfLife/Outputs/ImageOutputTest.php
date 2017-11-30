<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
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
    private $testDirectory = __DIR__ . "/../ImageOutputTest";
    /** @var FileSystemHandler */
    private $fileSystemHandler;

    protected function setUp()
    {
        $this->fileSystemHandler = new FileSystemHandler();
        $this->output = new ImageOutput("test", $this->testDirectory);
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->testDirectory, true);
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
        $output = new ImageOutput($_optionPrefix, $_imageOutputDirectory);

        $this->assertEquals($_optionPrefix, $output->optionPrefix());
        $this->assertEquals($_imageOutputDirectory, $output->imageOutputDirectory());
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
        $imageCreator = new ImageCreator(1,2,1, $colorBlack, $colorBlack, $colorBlack,"foo");

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setImageCreator($imageCreator);
        $this->output->setImageOutputDirectory($_imageOutputDirectory);
        $this->output->setOptionPrefix($_optionPrefix);

        $this->assertEquals($fileSystemHandler, $this->output->fileSystemHandler());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
        $this->assertEquals($_imageOutputDirectory, $this->output->imageOutputDirectory());
        $this->assertEquals($_optionPrefix, $this->output->optionPrefix());
    }

    /**
     * @covers \Output\ImageOutput::startOutput()
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
        $baseImage = $imageCreator->baseImage();

        $this->assertEquals(10, imagesx($baseImage));
        $this->assertEquals(10, imagesy($baseImage));
        $this->assertEquals(10, $imageCreator->cellSize());
    }

    /**
     * @covers \Output\ImageOutput::startOutput()
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

        $this->assertEquals(100, $imageCreator->cellSize());
    }
}
