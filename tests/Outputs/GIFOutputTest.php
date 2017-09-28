<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Output\GifOutput;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\GifOutput works as expected
 */
class GIFOutputTest extends TestCase
{
    /** @var GifOutput $output */
    private $output;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../GifOutputTest/";

    protected function setUp()
    {
        $this->output = new GifOutput();
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
     * @dataProvider setAttributesProvider()
     * @covers \Output\GifOutput::frames()
     * @covers \Output\GifOutput::setFrames()
     * @covers \Output\GifOutput::frameTime()
     * @covers \Output\GifOutput::setFrameTime()
     * @covers \Output\GifOutput::fileSystemHandler()
     * @covers \Output\GifOutput::setFileSystemHandler()
     * @covers \Output\GifOutput::imageCreator()
     * @covers \Output\GifOutput::setImageCreator()
     *
     * @param array $_frames    Frame save paths
     * @param int $_frameTime   Time per frame
     */
    public function testCanSetAttributes(array $_frames, int $_frameTime)
    {
        $fileSystemHandler = new FileSystemHandler();
        $colorBlack = new ImageColor(0, 0, 0);
        $imageCreator = new ImageCreator(2, 2, 2, $colorBlack, $colorBlack, $colorBlack, "tmp");

        $this->output->setFrames($_frames);
        $this->output->setFrameTime($_frameTime);
        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setImageCreator($imageCreator);

        $this->assertEquals($_frames, $this->output->frames());
        $this->assertEquals($_frameTime, $this->output->frameTime());
        $this->assertEquals($fileSystemHandler, $this->output->fileSystemHandler());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
    }

    public function setAttributesProvider()
    {
        return [
            [array("a/b", "a/c"), 20],
            [array("1/2", "1/3", "1/1"), 123],
            [array("hallo/be", "hallo/ggg", "asd/sdfs", "asdaf/sdfsdf"), 676]
        ];
    }

    /**
     * @covers \Output\GifOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $pngOutputOptions = array(
            array(null, "gifOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for gif outputs"),
            array(null, "gifOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for gif outputs"),
            array(null, "gifOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Background color for gif outputs"),
            array(null, "gifOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Grid color for gif outputs"),
            array(null, "gifOutputFrameTime", Getopt::REQUIRED_ARGUMENT, "Frame time of gif (in milliseconds * 10)"));

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($pngOutputOptions);

        if ($this->optionsMock instanceof Getopt) $this->output->addOptions($this->optionsMock);
    }


    /**
     * @covers \Output\GifOutput::startOutput()
     */
    public function testCanCreateOutputDirectory()
    {
        $this->assertFalse(file_exists($this->outputDirectory));

        $this->expectOutputString("Starting GIF Output...\n");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertTrue(file_exists($this->outputDirectory . "Gif"));
        $this->assertTrue(file_exists($this->outputDirectory . "tmp/Frames"));
    }

    /**
     * @covers \Output\GifOutput::outputBoard()
     * @covers \Output\GifOutput::finishOutput()
     */
    public function testCanCreateGif()
    {
        $this->expectOutputRegex("/.*Starting GIF Output...\n.*/");
        $this->output->startOutput(new Getopt(), $this->board);

        // Create gif frames and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->expectOutputRegex("/.*Gamestep: " . ($i + 1) . ".*/");
            $this->output->outputBoard($this->board);
            $this->board->calculateStep();
            $this->assertTrue(file_exists($this->outputDirectory . "tmp/Frames/" . $i . ".gif"));
        }

        // Check whether finishOutput creates the final gif
        $outputRegex = "Simulation finished. All cells are dead or a repeating pattern was detected.\n";
        $outputRegex .= "Starting GIF creation. One moment please...\n";
        $outputRegex .= "GIF creation complete.";

        $this->expectOutputRegex("/.*". $outputRegex . ".*/");
        $this->output->finishOutput();

        $this->assertTrue(file_exists($this->outputDirectory . "Gif/Game_1.gif"));
        $this->assertFalse(file_exists($this->outputDirectory . "tmp"));
    }

    /**
     * @covers \Output\GifOutput::finishOutput()
     */
    public function testDetectsEmptyFramesFolder()
    {
        $this->expectOutputRegex("/.*Error: No frames in frames folder found!\n/");
        $this->output->finishOutput();
    }

    /**
     * @covers \Output\GifOutput::finishOutput()
     */
    public function testDetectsOutputFolderNotExisting()
    {
        $this->output->startOutput(new Getopt(), $this->board);
        $this->output->outputBoard($this->board);

        $this->fileSystemHandler->deleteDirectory($this->output->outputDirectory() . "Gif");

        $this->expectOutputRegex("/.*An error occurred during the gif creation. Stopping.../");
        @$this->output->finishOutput();
    }
}
