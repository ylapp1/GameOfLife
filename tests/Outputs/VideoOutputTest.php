<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\VideoOutput;
use PHPUnit\Framework\TestCase;
use GameOfLife\Board;
use GameOfLife\RuleSet;
use Utils\FileSystemHandler;
use Ulrichsg\Getopt;

/**
 * Class VideoOutputTest
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
