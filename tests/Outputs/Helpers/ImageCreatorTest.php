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
    private $outputDirectory = __DIR__ . "/../../ImageCreatorTest";

    protected function setUp()
    {
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);

        $colorBlack = new ImageColor(0, 0, 0);
        $colorWhite = new ImageColor(255, 255, 255);

        $this->imageCreator = new ImageCreator($this->board->height(), $this->board->width(), 15, $colorBlack,
                                                $colorWhite, $colorBlack, $this->outputDirectory);
        $this->imageCreator->setOutputPath($this->outputDirectory . "/tmp/Frames");
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
        $outputPath = $this->outputDirectory . "/tmp/Frames/";
        $this->expectOutputRegex("/.*Gamestep: 1.*/");

        $this->imageCreator->createImage($this->board, "png");
        $this->assertTrue(file_exists($outputPath . "0.png"));
        $this->fileSystemHandler->deleteDirectory($outputPath);

        $this->imageCreator->createImage($this->board, "video");
        $this->assertTrue(file_exists($outputPath . "0.png"));
        $this->fileSystemHandler->deleteDirectory($outputPath);

        $this->imageCreator->createImage($this->board, "gif");
        $this->assertTrue(file_exists($outputPath . "0.gif"));
        $this->fileSystemHandler->deleteDirectory($outputPath);

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

        $this->assertEquals(15, $imageCreator->cellSize());
        $this->assertEquals($colorWhite, $imageCreator->backgroundColor());
        $this->assertEquals($colorBlack, $imageCreator->cellAliveColor());
        $this->assertEquals($colorBlack, $imageCreator->gridColor());
        $this->assertEquals("tmp/Frames", $imageCreator->outputPath());
        $this->assertTrue(is_resource($imageCreator->baseImage()));
        $this->assertTrue(is_resource($imageCreator->cellImage()));
        $this->assertEquals(new FileSystemHandler(), $imageCreator->fileSystemHandler());
    }

    /**
     * @dataProvider setAttributesProvider
     * @covers \Output\Helpers\ImageCreator::setOutputPath()
     * @covers \Output\Helpers\ImageCreator::outputPath()
     * @covers \Output\Helpers\ImageCreator::setCellSize()
     * @covers \Output\Helpers\ImageCreator::cellSize()
     * @covers \Output\Helpers\ImageCreator::setBackgroundColor()
     * @covers \Output\Helpers\ImageCreator::backgroundColor()
     * @covers \Output\Helpers\ImageCreator::setCellAliveColor()
     * @covers \Output\Helpers\ImageCreator::cellAliveColor()
     * @covers \Output\Helpers\ImageCreator::setGridColor()
     * @covers \Output\Helpers\ImageCreator::gridColor()
     * @covers \Output\Helpers\ImageCreator::setBaseImage()
     * @covers \Output\Helpers\ImageCreator::baseImage()
     * @covers \Output\Helpers\ImageCreator::setCellImage()
     * @covers \Output\Helpers\ImageCreator::cellImage()
     * @covers \Output\Helpers\ImageCreator::setFileSystemHandler()
     * @covers \Output\Helpers\ImageCreator::fileSystemHandler()
     *
     * @param int $_cellSize        Diameter/Width/Height of a single cell
     * @param string $_outputPath   Path where images are saved
     */
    public function testCanSetAttributes(int $_cellSize, string $_outputPath)
    {
        $baseImage = imagecreate(rand(1, 10), rand(1, 10));
        $cellImage = imagecreate(rand(1, 10), rand(1, 10));

        $backgroundColor = new ImageColor(rand(0, 255), rand(0, 255), rand(0, 255));
        $cellAliveColor = new ImageColor(rand(0, 255), rand(0, 255), rand(0, 255));
        $gridColor = new ImageColor(rand(0, 255), rand(0, 255), rand(0, 255));

        $fileSystemHandler = new FileSystemHandler();

        $this->imageCreator->setCellSize($_cellSize);
        $this->imageCreator->setBackgroundColor($backgroundColor);
        $this->imageCreator->setCellAliveColor($cellAliveColor);
        $this->imageCreator->setGridColor($gridColor);
        $this->imageCreator->setOutputPath($_outputPath);
        $this->imageCreator->setBaseImage($baseImage);
        $this->imageCreator->setCellImage($cellImage);
        $this->imageCreator->setFileSystemHandler($fileSystemHandler);

        $this->assertEquals($_cellSize, $this->imageCreator->cellSize());
        $this->assertEquals($backgroundColor, $this->imageCreator->backgroundColor());
        $this->assertEquals($cellAliveColor, $this->imageCreator->cellAliveColor());
        $this->assertEquals($gridColor, $this->imageCreator->gridColor());
        $this->assertEquals($_outputPath, $this->imageCreator->outputPath());
        $this->assertEquals($baseImage, $this->imageCreator->baseImage());
        $this->assertEquals($cellImage, $this->imageCreator->cellImage());
        $this->assertEquals($fileSystemHandler, $this->imageCreator->fileSystemHandler());
    }

    public function setAttributesProvider()
    {
        return [
            [4, "other/path"],
            [444, "my/other/path"],
            [123, "my/path"]
        ];
    }
}
