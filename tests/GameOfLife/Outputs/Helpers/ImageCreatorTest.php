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
        try
        {
            $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }
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
     *
     * @throws \ReflectionException
     */
    public function testCanBeConstructed()
    {
        $colorBlack = new ImageColor(0, 0, 0);
        $colorWhite = new ImageColor(255, 255, 255);

        $imageCreator = new ImageCreator($this->board->height(), $this->board->width(), 15, $colorBlack,
                                         $colorWhite, $colorBlack);

        $this->assertEquals(15, \getPrivateAttribute($imageCreator, "cellSize"));
        $this->assertTrue(is_resource(\getPrivateAttribute($imageCreator, "baseImage")));
        $this->assertTrue(is_resource(\getPrivateAttribute($imageCreator, "cellImage")));
    }
}
