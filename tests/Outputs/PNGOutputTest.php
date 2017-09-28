<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Output\PNGOutput;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\PNGOutput works as expected
 */
class PNGOutputTest extends TestCase
{
    /** @var PNGOutput $output */
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
        $this->output = new PNGOutput();
        $this->output->setOutputDirectory($this->outputDirectory);
        $this->fileSystemHandler = new FileSystemHandler();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);

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
     * @covers \Output\PNGOutput::fileSystemHandler()
     * @covers \Output\PNGOutput::setFileSystemHandler()
     * @covers \Output\PNGOutput::imageCreator()
     * @covers \Output\PNGOutput::setImageCreator()
     */
    public function testCanSetAttributes()
    {
        $fileSystemHandler = new FileSystemHandler();
        $colorBlack = new ImageColor(0, 0, 0);
        $imageCreator = new ImageCreator(1, 2, 3, $colorBlack, $colorBlack, $colorBlack, "tmp");

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setImageCreator($imageCreator);

        $this->assertEquals($fileSystemHandler,  $this->output->fileSystemHandler());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
    }

    /**
     * @covers \Output\PNGOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $pngOutputOptions = array(
            array(null, "pngOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for PNG outputs"),
            array(null, "pngOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for PNG outputs"),
            array(null, "pngOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Color of the background for PNG outputs"),
            array(null, "pngOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Color of the grid for PNG outputs")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($pngOutputOptions);

        if ($this->optionsMock instanceof Getopt) $this->output->addOptions($this->optionsMock);
    }

    /**
     * @covers \Output\PNGOutput::startOutput()
     */
    public function testCanCreateOutputDirectory()
    {
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);
        $this->assertFalse(file_exists($this->outputDirectory));

        $this->expectOutputString("Starting simulation ...\n\n");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertTrue(file_exists($this->outputDirectory . "PNG/Game_1"));
    }

    /**
     * @covers \Output\PNGOutput::outputBoard()
     */
    public function testCanCreatePNG()
    {
        $this->expectOutputRegex("/.*Starting simulation ...\n\n.*/");
        $this->output->startOutput(new Getopt(), $this->board);

        // Create pngs and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->expectOutputRegex("/.*Gamestep: " . ($i + 1) . ".*/");
            $this->output->outputBoard($this->board);
            $this->board->calculateStep();
            $this->assertTrue(file_exists($this->outputDirectory . "PNG/Game_1/" . $i . ".png"));
        }
    }

    /**
     * @covers \Output\PNGOutput::finishOutput()
     */
    public function testCanFinishOutput()
    {
        $this->expectOutputString("\n\nSimulation finished. All cells are dead or a repeating pattern was detected.");
        $this->output->finishOutput();
    }
}