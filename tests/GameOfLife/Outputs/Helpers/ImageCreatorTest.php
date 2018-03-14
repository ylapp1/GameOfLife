<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn.consult.eu>
 */

use GameOfLife\Board;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\Helpers\ImageCreator works as expected.
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
        $this->board = new Board(10, 10, 50, true);

        $colorBlack = new ImageColor(0, 0, 0);
        $colorWhite = new ImageColor(255, 255, 255);

        $this->imageCreator = new ImageCreator($this->board->height(), $this->board->width(), 15, $colorBlack,
                                                $colorWhite, $colorBlack);
        $this->fileSystemHandler = new FileSystemHandler();
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);
        unset($this->fileSystemHandler);
        unset($this->imageCreator);
        unset($this->board);
    }

    /**
     * @covers \Output\Helpers\ImageCreator::createImage()
     */
    public function testCanCreateImage()
    {
        $image = $this->imageCreator->createImage($this->board);
        $this->assertEquals("gd", get_resource_type($image));

        unset($image);
    }


    /**
     * @covers \Output\Helpers\ImageCreator::__construct()
     * @covers \Output\Helpers\ImageCreator::initializeBaseImage()
     * @covers \Output\Helpers\ImageCreator::initializeCellImage()
     */
    public function testCanBeConstructed()
    {
        $colorBlack = new ImageColor(0, 0, 0);
        $colorWhite = new ImageColor(255, 255, 255);

        $imageCreator = new ImageCreator($this->board->height(), $this->board->width(), 15, $colorBlack,
                                         $colorWhite, $colorBlack);

        $this->assertEquals(15, $imageCreator->cellSize());
        $this->assertTrue(is_resource($imageCreator->baseImage()));
        $this->assertTrue(is_resource($imageCreator->cellImage()));
        $this->assertEquals(new FileSystemHandler(), $imageCreator->fileSystemHandler());
    }

    /**
     * Checks whether the getters/setters work as expected.
     *
     * @dataProvider setAttributesProvider
     * @covers \Output\Helpers\ImageCreator::setCellSize()
     * @covers \Output\Helpers\ImageCreator::cellSize()
     * @covers \Output\Helpers\ImageCreator::setBaseImage()
     * @covers \Output\Helpers\ImageCreator::baseImage()
     * @covers \Output\Helpers\ImageCreator::setCellImage()
     * @covers \Output\Helpers\ImageCreator::cellImage()
     * @covers \Output\Helpers\ImageCreator::setFileSystemHandler()
     * @covers \Output\Helpers\ImageCreator::fileSystemHandler()
     *
     * @param int $_cellSize        Diameter/Width/Height of a single cell
     */
    public function testCanSetAttributes(int $_cellSize)
    {
        $baseImage = imagecreate(rand(1, 10), rand(1, 10));
        $cellImage = imagecreate(rand(1, 10), rand(1, 10));
        $fileSystemHandler = new FileSystemHandler();

        $this->imageCreator->setCellSize($_cellSize);
        $this->imageCreator->setBaseImage($baseImage);
        $this->imageCreator->setCellImage($cellImage);
        $this->imageCreator->setFileSystemHandler($fileSystemHandler);

        $this->assertEquals($_cellSize, $this->imageCreator->cellSize());
        $this->assertEquals($baseImage, $this->imageCreator->baseImage());
        $this->assertEquals($cellImage, $this->imageCreator->cellImage());
        $this->assertEquals($fileSystemHandler, $this->imageCreator->fileSystemHandler());
    }

    public function setAttributesProvider()
    {
        return [
            [4],
            [444],
            [123]
        ];
    }
}
