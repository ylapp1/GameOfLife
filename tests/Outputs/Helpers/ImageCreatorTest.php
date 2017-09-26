<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn.consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\Helpers\ImageCreator works as expected
 */
class ImageCreatorTest extends TestCase
{
    /** @var ImageCreator */
    private $imageCreator;
    /** @var Board */
    private $board;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../../../Output/tmp/Frames/";

    protected function setUp()
    {
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);

        $colorBlack = new ImageColor(0, 0, 0);
        $colorWhite = new ImageColor(255, 255, 255);

        $this->imageCreator = new ImageCreator($this->board->height(), $this->board->width(), 15, $colorBlack,
                                                $colorWhite, $colorBlack, "tmp/Frames");
        $this->fileSystemHandler = new FileSystemHandler();
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);
        unset($this->imageCreator);
        unset($this->board);
    }

    /**
     * @covers \Output\Helpers\ImageCreator::createImage()
     */
    public function testCanCreateImage()
    {
        $this->expectOutputRegex("/.*Gamestep: 1.*/");

        $this->imageCreator->createImage($this->board, "png");
        $this->assertEquals(true, file_exists($this->outputDirectory . "0.png"));
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory);

        $this->imageCreator->createImage($this->board, "video");
        $this->assertEquals(true, file_exists($this->outputDirectory . "0.png"));
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory);

        $this->imageCreator->createImage($this->board, "gif");
        $this->assertEquals(true, file_exists($this->outputDirectory . "0.gif"));
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory);

        $this->expectOutputRegex("/.*Error: Invalid image type specified!\n.*/");
        $this->imageCreator->createImage($this->board, "myInvalidImageType");
    }


    /**
     * @covers \Output\Helpers\ImageCreator::__construct
     */
    public function testCanBeConstructed()
    {
        $colorBlack = new ImageColor(0, 0, 0);
        $colorWhite = new ImageColor(255, 255, 255);

        $imageCreator = new ImageCreator($this->board->height(), $this->board->width(), 15, $colorBlack,
                                         $colorWhite, $colorBlack, "tmp/Frames");

        $this->assertStringEndsWith('/../../../../Output/', $imageCreator->basePath());
        $this->assertEquals(15, $imageCreator->cellSize());
        $this->assertEquals($colorWhite, $imageCreator->backgroundColor());
        $this->assertEquals($colorBlack, $imageCreator->cellAliveColor());
        $this->assertEquals($colorBlack, $imageCreator->gridColor());
        $this->assertEquals("tmp/Frames", $imageCreator->gameFolder());
        $this->assertEquals(true, is_resource($imageCreator->baseImage()));
        $this->assertEquals(true, is_resource($imageCreator->cellImage()));
    }

    /**
     * @dataProvider setAttributesProvider
     * @covers \Output\Helpers\ImageCreator::setBasePath()
     * @covers \Output\Helpers\ImageCreator::basePath()
     * @covers \Output\Helpers\ImageCreator::setCellSize()
     * @covers \Output\Helpers\ImageCreator::cellSize()
     * @covers \Output\Helpers\ImageCreator::setBackgroundColor()
     * @covers \Output\Helpers\ImageCreator::backgroundColor()
     * @covers \Output\Helpers\ImageCreator::setCellAliveColor()
     * @covers \Output\Helpers\ImageCreator::cellAliveColor()
     * @covers \Output\Helpers\ImageCreator::setGridColor()
     * @covers \Output\Helpers\ImageCreator::gridColor()
     * @covers \Output\Helpers\ImageCreator::setGameFolder()
     * @covers \Output\Helpers\ImageCreator::gameFolder()
     * @covers \Output\Helpers\ImageCreator::setBaseImage()
     * @covers \Output\Helpers\ImageCreator::baseImage()
     * @covers \Output\Helpers\ImageCreator::setCellImage()
     * @covers \Output\Helpers\ImageCreator::cellImage()
     *
     * @param string $_basePath
     * @param int $_cellSize
     * @param string $_gameFolder
     */
    public function testCanSetAttributes(string $_basePath, int $_cellSize, string $_gameFolder)
    {
        $baseImage = imagecreate(rand(1, 10), rand(1, 10));
        $cellImage = imagecreate(rand(1, 10), rand(1, 10));

        $backgroundColor = new ImageColor(rand(0, 255), rand(0, 255), rand(0, 255));
        $cellAliveColor = new ImageColor(rand(0, 255), rand(0, 255), rand(0, 255));
        $gridColor = new ImageColor(rand(0, 255), rand(0, 255), rand(0, 255));


        $this->imageCreator->setBasePath($_basePath);
        $this->imageCreator->setCellSize($_cellSize);
        $this->imageCreator->setBackgroundColor($backgroundColor);
        $this->imageCreator->setCellAliveColor($cellAliveColor);
        $this->imageCreator->setGridColor($gridColor);
        $this->imageCreator->setGameFolder($_gameFolder);
        $this->imageCreator->setBaseImage($baseImage);
        $this->imageCreator->setCellImage($cellImage);

        $this->assertEquals($_basePath, $this->imageCreator->basePath());
        $this->assertEquals($_cellSize, $this->imageCreator->cellSize());
        $this->assertEquals($backgroundColor, $this->imageCreator->backgroundColor());
        $this->assertEquals($cellAliveColor, $this->imageCreator->cellAliveColor());
        $this->assertEquals($gridColor, $this->imageCreator->gridColor());
        $this->assertEquals($_gameFolder, $this->imageCreator->gameFolder());
        $this->assertEquals($baseImage, $this->imageCreator->baseImage());
        $this->assertEquals($cellImage, $this->imageCreator->cellImage());
    }

    public function setAttributesProvider()
    {
        return [
            ["path", 4, "other/path"],
            ["path/to/file", 444, "my/other/path"],
            ["a/test/path", 123, "my/path"]
        ];
    }
}
