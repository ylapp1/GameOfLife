<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use Simulator\GameLogic;
use Output\Helpers\FfmpegHelper;
use Output\VideoOutput;
use Rule\ConwayRule;
use Ulrichsg\Getopt;
use Utils\FileSystem\FileSystemWriter;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\VideoOutput works as expected.
 */
class VideoOutputTest extends TestCase
{
    /** @var VideoOutput $output */
    private $output;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;
    /** @var FileSystemWriter */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../VideoOutputTest/";
    /** @var GameLogic $gameLogic */
    private $gameLogic;

    /**
     * @throws Exception
     */
    protected function setUp()
    {
        $this->output = new VideoOutput();
        $this->output->setBaseOutputDirectory($this->outputDirectory);
        $this->output->setImageOutputDirectory($this->outputDirectory . "/tmp/Frames");
        $this->fileSystemHandler = new FileSystemWriter();

        $this->board = new Board(10, 10, true);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();

        $this->gameLogic = new GameLogic(new ConwayRule(), 1);
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

        unset($this->output);
        unset($this->board);
        unset($this->optionsMock);
        unset($this->fileSystemHandler);
        unset($this->gameLogic);
    }


    /**
     * @covers \Output\VideoOutput::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $output = new VideoOutput();

        $this->assertEquals("video", $output->optionPrefix());
        $this->assertNotFalse(stristr($output->imageOutputDirectory(), "/tmp/Frames"));
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
     * @covers \Output\VideoOutput::hasSound()
     * @covers \Output\VideoOutput::setHasSound()
     * @covers \Output\VideoOutput::ffmpegHelper()
     * @covers \Output\VideoOutput::setFfmpegHelper()
     *
     * @param array $_fillPercentages       Fill percentage of each gamestep
     * @param int $_fps                     Frames per second
     * @param array $_frames                Frame paths
     * @param bool $_hasSound               Indicates whether the video will have sound or not
     *
     * @throws \Exception
     */
    public function testCanSetAttributes(array $_fillPercentages, int $_fps, array $_frames, bool $_hasSound)
    {
        $fileSystemHandler = new FileSystemWriter();
        $ffmpegHelper = new FfmpegHelper();

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setFillPercentages($_fillPercentages);
        $this->output->setFps($_fps);
        $this->output->setFrames($_frames);
        $this->output->setHasSound($_hasSound);
        $this->output->setFfmpegHelper($ffmpegHelper);

        $this->assertEquals($fileSystemHandler, $this->output->fileSystemHandler());
        $this->assertEquals($_fillPercentages, $this->output->fillPercentages());
        $this->assertEquals($_fps, $this->output->fps());
        $this->assertEquals($_frames, $this->output->frames());
        $this->assertEquals($_hasSound, $this->output->hasSound());
        $this->assertEquals($ffmpegHelper, $this->output->ffmpegHelper());
    }

    public function setAttributesProvider()
    {
        return [
            [array(1, 2, 3), 15, array("a/2", "a/3", "a/4"), true],
            [array(4, 5, 6), 234, array("b/2", "b/4", "b/6/6"), false]
        ];
    }

    /**
     * @covers \Output\VideoOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $videoOutputOptions = array(
            array(null, "videoOutputFPS", Getopt::REQUIRED_ARGUMENT, "VideoOutput - Frames per second of videos"),
            array(null, "videoOutputAddSound", Getopt::NO_ARGUMENT, "VideoOutput - Add sound to the video")
        );

        $imageOutputOptions = array(
            array(null, "videoOutputSize", Getopt::REQUIRED_ARGUMENT, "VideoOutput - Size of a cell in pixels"),
            array(null, "videoOutputCellColor", Getopt::REQUIRED_ARGUMENT, "VideoOutput - Color of a cell"),
            array(null, "videoOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "VideoOutput - Background color"),
            array(null, "videoOutputGridColor", Getopt::REQUIRED_ARGUMENT, "VideoOutput - Grid color\n"));

        $this->optionsMock->expects($this->exactly(2))
                          ->method("addOptions")
                          ->withConsecutive(array($videoOutputOptions), array($imageOutputOptions));

        if ($this->optionsMock instanceof Getopt) $this->output->addOptions($this->optionsMock);
    }


    /**
     * @covers \Output\VideoOutput::startOutput()
     *
     * @throws \Exception
     */
    public function testCanCreateOutputDirectory()
    {
        $this->assertEquals(false, file_exists($this->outputDirectory));

        $this->expectOutputRegex("/Starting video output ...\n\n.*/");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertTrue(file_exists($this->outputDirectory . "Video"));
        $this->assertTrue(file_exists($this->outputDirectory . "tmp/Frames"));
        $this->assertTrue(file_exists($this->outputDirectory . "tmp/Audio"));
    }

    /**
     * Checks whether a video can be successfully created.
     *
     * @dataProvider createVideoProvider()
     * @covers \Output\VideoOutput::outputBoard()
     * @covers \Output\VideoOutput::finishOutput()
     * @covers \Output\BaseOutput::finishOutput()
     * @covers \Output\VideoOutput::generateVideoFile()
     *
     * @param bool $_hasSound   Indicates whether the video will have sound or not
     *
     * @throws \Exception
     */
    public function testCanCreateVideo(bool $_hasSound)
    {
        $ffmpegHelperMock = $this->getMockBuilder(\Output\Helpers\FfmpegHelper::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $ffmpegHelperMock->expects($this->exactly(1 + $_hasSound * 10))
                         ->method("executeCommand")
                         ->willReturn(false);

        if ($ffmpegHelperMock instanceof \Output\Helpers\FfmpegHelper)
        {
            $this->output->setFfmpegHelper($ffmpegHelperMock);
        }

        $this->expectOutputRegex("Starting video output ...\n\n");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->output->setHasSound($_hasSound);

        // Create video frames and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->expectOutputRegex("/.*Gamestep: " . $this->gameLogic->gameStep() . ".*/");
            $this->output->outputBoard($this->board, $this->gameLogic->gameStep());
            $this->gameLogic->calculateNextBoard($this->board);
            $this->assertTrue(file_exists($this->outputDirectory . "tmp/Frames/" . ($this->gameLogic->gameStep() - 1) . ".png"));
        }

        // Check whether finishOutput creates the final gif
        $outputRegex = "\nSimulation finished: All cells are dead.\n\n";
        $outputRegex .= "\nStarting video creation ...\n";

        if ($_hasSound == true)
        {
            for ($i = 0; $i < 10; $i++)
            {
                $outputRegex .= ".*\rGenerating audio ... " . ($i + 1) . "\\/10";
            }
        }

        $outputRegex .= "\nGenerating video file ...";
        $outputRegex .= "\nVideo creation complete!\n\n";

        $this->expectOutputRegex("/.*". $outputRegex . ".*/");
        $this->output->finishOutput("All cells are dead");

        //$this->assertEquals(true, file_exists($this->outputDirectory . "/Video/Game_1.mp4"));
        $this->assertFalse(file_exists($this->outputDirectory . "tmp"));
    }

    /**
     * DataProvider for VideoOutputTest::testCanCreateVideo().
     *
     * @return array Test values in the format array(hasSound)
     */
    public function createVideoProvider(): array
    {
        return array(
            "Without sound" => array(false),
            "With sound" => array(true)
        );
    }

    /**
     * Checks whether an empty frames folder is succesfully detected.
     *
     * @covers \Output\VideoOutput::finishOutput()
     * @covers \Output\VideoOutput::generateVideoFile()
     *
     * @throws \Exception
     */
    public function testDetectsEmptyFramesFolder()
    {
        // Hide output
        $this->expectOutputRegex("/.*/");

        $exceptionOccurred = false;
        try
        {
            $this->output->finishOutput("test");
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $this->assertEquals("No frames in frames folder found.", $_exception->getMessage());
        }
        $this->assertTrue($exceptionOccurred);
    }
}
