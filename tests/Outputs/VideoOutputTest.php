<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Output\VideoOutput;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\VideoOutput works as expected
 */
class VideoOutputTest extends TestCase
{
    /** @var VideoOutput $output */
    private $output;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../../Output/";

    protected function setUp()
    {
        $this->output = new VideoOutput();
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
     * @covers \Output\VideoOutput::fileSystemHandler()
     * @covers \Output\VideoOutput::setFileSystemHandler()
     * @covers \Output\VideoOutput::fillPercentages()
     * @covers \Output\VideoOutput::setFillPercentages()
     * @covers \Output\VideoOutput::fps()
     * @covers \Output\VideoOutput::setFps()
     * @covers \Output\VideoOutput::frames()
     * @covers \Output\VideoOutput::setFrames()
     * @covers \Output\VideoOutput::imageCreator()
     * @covers \Output\VideoOutput::setImageCreator()
     * @covers \Output\VideoOutput::secondsPerFrame()
     * @covers \Output\VideoOutput::setSecondsPerFrame()
     *
     * @param array $_fillPercentages
     * @param int $_fps
     * @param array $_frames
     * @param float $_secondsPerFrame
     */
    public function testCanSetAttributes(array $_fillPercentages, int $_fps, array $_frames, float $_secondsPerFrame)
    {
        $fileSystemHandler = new FileSystemHandler();
        $colorBlack = new ImageColor(0, 0, 0);
        $imageCreator = new ImageCreator(1, 2, 3, $colorBlack, $colorBlack, $colorBlack);

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setFillPercentages($_fillPercentages);
        $this->output->setFps($_fps);
        $this->output->setFrames($_frames);
        $this->output->setImageCreator($imageCreator);
        $this->output->setSecondsPerFrame($_secondsPerFrame);

        $this->assertEquals($fileSystemHandler, $this->output->fileSystemHandler());
        $this->assertEquals($_fillPercentages, $this->output->fillPercentages());
        $this->assertEquals($_fps, $this->output->fps());
        $this->assertEquals($_frames, $this->output->frames());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
        $this->assertEquals($_secondsPerFrame, $this->output->secondsPerFrame());
    }

    public function setAttributesProvider()
    {
        return [
            [array(1, 2, 3), 15, array("a/2", "a/3", "a/4"), 40.6],
            [array(4, 5, 6), 234, array("b/2", "b/4", "b/6/6"), 70.3]
        ];
    }

    /**
     * @covers \Output\VideoOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $pngOutputOptions = array(
            array(null, "videoOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for video outputs"),
            array(null, "videoOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for video outputs"),
            array(null, "videoOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Background color for video outputs"),
            array(null, "videoOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Grid color for video outputs"),
            array(null, "videoOutputFPS", Getopt::REQUIRED_ARGUMENT, "Frames per second of videos"));

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($pngOutputOptions);

        $this->output->addOptions($this->optionsMock);
    }


    /**
     * @covers \Output\VideoOutput::startOutput()
     */
    public function testCanCreateOutputDirectory()
    {
        $this->assertEquals(false, file_exists($this->outputDirectory));

        $this->expectOutputString("Starting video output ...\n");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertEquals(true, file_exists($this->outputDirectory . "Video"));
        $this->assertEquals(true, file_exists($this->outputDirectory . "tmp/Frames"));
        $this->assertEquals(true, file_exists($this->outputDirectory . "tmp/Audio"));
    }

    /**
     * @covers \Output\VideoOutput::outputBoard()
     * @covers \Output\VideoOutput::finishOutput()
     */
    public function testCanCreateVideo()
    {
        $this->expectOutputRegex("Starting video output ...\n");
        $this->output->startOutput(new Getopt(), $this->board);

        // Create video frames and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->expectOutputRegex("/.*Gamestep: " . ($i + 1) . ".*/");
            $this->output->outputBoard($this->board);
            $this->board->calculateStep();
            $this->assertEquals(true, file_exists($this->outputDirectory . "tmp/Frames/" . $i . ".png"));
        }

        // Check whether finishOutput creates the final gif
        $outputRegex = "\nSimulation finished. All cells are dead or a repeating pattern was detected.";
        $outputRegex .= "\nStarting video creation ...\n";

        for ($i = 0; $i < 10; $i++)
        {
            $outputRegex .= ".*\rGenerating audio ... " . ($i + 1) . "\\/10";
        }

        $outputRegex .= "\nGenerating video file ...";
        $outputRegex .= "\nVideo creation complete!\n\n";

        $this->expectOutputRegex("/.*". $outputRegex . ".*/");
        $this->output->finishOutput();

        //$this->assertEquals(true, file_exists($this->outputDirectory . "Video/Game_0.mp4"));
        $this->assertEquals(false, file_exists($this->outputDirectory . "tmp"));
    }

    /**
     * @covers \Output\VideoOutput::finishOutput()
     */
    public function testDetectsEmptyFramesFolder()
    {
        $this->expectOutputRegex("/.*Error: No frames in frames folder found!\n/");
        $this->output->finishOutput();
    }
}
