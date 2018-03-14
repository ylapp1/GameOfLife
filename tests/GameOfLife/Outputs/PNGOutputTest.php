<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\GameLogic;
use Output\PngOutput;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Rule\ConwayRule;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\PngOutput works as expected.
 */
class PNGOutputTest extends TestCase
{
    /** @var PngOutput $output */
    private $output;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../PNGOutputTest/";

    protected function setUp()
    {
        $this->output = new PngOutput();
        $this->output->setBaseOutputDirectory($this->outputDirectory);
        $this->output->setImageOutputDirectory($this->outputDirectory . "/PNG/Game_1/");
        $this->fileSystemHandler = new FileSystemHandler();

        $this->board = new Board(10, 10, 50, true);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);

        unset($this->output);
        unset($this->board);
        unset($this->optionsMock);
        unset($this->fileSystemHandler);
    }


    /**
     * @covers \Output\PngOutput::__construct()
     */
    public function testCanBeConstructed()
    {
        $output = new PngOutput();

        $this->assertEquals("png", $output->optionPrefix());
        $this->assertNotFalse(stristr($output->imageOutputDirectory(), "/PNG/"));
    }

    /**
     * @covers \Output\PngOutput::fileSystemHandler()
     * @covers \Output\PngOutput::setFileSystemHandler()
     * @covers \Output\PngOutput::imageCreator()
     * @covers \Output\PngOutput::setImageCreator()
     */
    public function testCanSetAttributes()
    {
        $fileSystemHandler = new FileSystemHandler();
        $colorBlack = new ImageColor(0, 0, 0);
        $imageCreator = new ImageCreator(1, 2, 3, $colorBlack, $colorBlack, $colorBlack);

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setImageCreator($imageCreator);

        $this->assertEquals($fileSystemHandler,  $this->output->fileSystemHandler());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
    }

    /**
     * @covers \Output\PngOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $pngOutputOptions = array(
            array(null, "pngOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels"),
            array(null, "pngOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell"),
            array(null, "pngOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Background color"),
            array(null, "pngOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Grid color")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($pngOutputOptions);

        if ($this->optionsMock instanceof Getopt) $this->output->addOptions($this->optionsMock);
    }

    /**
     * @covers \Output\PngOutput::startOutput()
     */
    public function testCanCreateOutputDirectory()
    {
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);
        $this->assertFalse(file_exists($this->outputDirectory));

        $this->expectOutputString("Starting PNG Output ...\n\n");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertTrue(file_exists($this->outputDirectory . "PNG/Game_1"));
    }

    /**
     * @covers \Output\PngOutput::outputBoard()
     */
    public function testCanCreatePNG()
    {
        $gameLogic = new GameLogic(new ConwayRule());
        $this->expectOutputRegex("/.*Starting simulation ...\n\n.*/");
        $this->output->startOutput(new Getopt(), $this->board);

        // Create pngs and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->expectOutputRegex("/.*Gamestep: " . ($i + 1) . ".*/");
            $this->output->outputBoard($this->board);
            $gameLogic->calculateNextBoard($this->board);
            $this->assertTrue(file_exists($this->outputDirectory . "PNG/Game_1/" . $i . ".png"));
        }
    }

    /**
     * @covers \Output\PngOutput::finishOutput()
     * @covers \Output\BaseOutput::finishOutput()
     */
    public function testCanFinishOutput()
    {
        $this->expectOutputString("\nSimulation finished: All cells are dead.\n\n");
        $this->output->finishOutput("All cells are dead");
    }
}
