<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn.consult.eu>
 */

use Output\Helpers\ImageCreator;
use Output\Helpers\ImageColor;
use PHPUnit\Framework\TestCase;
use GameOfLife\Board;
use GameOfLife\RuleSet;
use GameOfLife\FileSystemHandler;
use Ulrichsg\Getopt;

/**
 * Class ImageCreatorTest
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


    public function testCanBeConstructed()
    {
        $colorBlack = new ImageColor(0, 0, 0);
        $colorWhite = new ImageColor(255, 255, 255);

        $imageCreator = new ImageCreator($this->board->height(), $this->board->width(), 15, $colorBlack,
            $colorWhite, $colorBlack, "tmp/Frames");

        $this->assertEquals(__DIR__ . "/../../../../Output/", $imageCreator->basePath());
    }
}
