<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\GameLogic;
use Output\Helpers\FfmpegHelper;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Output\VideoOutput;
use Rule\ConwayRule;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
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
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../VideoOutputTest/";
    /** @var GameLogic $gameLogic */
    private $gameLogic;

    protected function setUp()
    {
        $this->output = new VideoOutput();
        $this->output->setBaseOutputDirectory($this->outputDirectory);
        $this->output->setImageOutputDirectory($this->outputDirectory . "/tmp/Frames");
        $this->fileSystemHandler = new FileSystemHandler();

        $this->board = new Board(10, 10, 50, true);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();

        $this->gameLogic = new GameLogic(new ConwayRule());
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);

        unset($this->output);
        unset($this->board);
        unset($this->optionsMock);
        unset($this->fileSystemHandler);
        unset($this->gameLogic);
    }


    /**
     * @covers \Output\VideoOutput::__construct()
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
     * @covers \Output\VideoOutput::imageCreator()
     * @covers \Output\VideoOutput::setImageCreator()
     * @covers \Output\VideoOutput::ffmpegHelper()
     * @covers \Output\VideoOutput::setFfmpegHelper()
     *
     * @param array $_fillPercentages       Fill percentage of each gamestep
     * @param int $_fps                     Frames per second
     * @param array $_frames                Frame paths
     * @param bool $_hasSound               Indicates whether the video will have sound or not
     */
    public function testCanSetAttributes(array $_fillPercentages, int $_fps, array $_frames, bool $_hasSound)
    {
        $fileSystemHandler = new FileSystemHandler();
        $colorBlack = new ImageColor(0, 0, 0);
        $imageCreator = new ImageCreator(1, 2, 3, $colorBlack, $colorBlack, $colorBlack, "tmp");
        $ffmpegHelper = new FfmpegHelper("Mein lustiger test");

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setFillPercentages($_fillPercentages);
        $this->output->setFps($_fps);
        $this->output->setFrames($_frames);
        $this->output->setImageCreator($imageCreator);
        $this->output->setHasSound($_hasSound);
        $this->output->setFfmpegHelper($ffmpegHelper);

        $this->assertEquals($fileSystemHandler, $this->output->fileSystemHandler());
        $this->assertEquals($_fillPercentages, $this->output->fillPercentages());
        $this->assertEquals($_fps, $this->output->fps());
        $this->assertEquals($_frames, $this->output->frames());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
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
        $imageOutputOptions = array(
            array(null, "videoOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels"),
            array(null, "videoOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell"),
            array(null, "videoOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Background color"),
            array(null, "videoOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Grid color"));

        $videoOutputOptions = array(
            array(null, "videoOutputFPS", Getopt::REQUIRED_ARGUMENT, "Frames per second of videos"),
            array(null, "videoOutputAddSound", Getopt::NO_ARGUMENT, "Add sound to the video")
        );

        $this->optionsMock->expects($this->exactly(2))
                          ->method("addOptions")
                          ->withConsecutive([$imageOutputOptions], [$videoOutputOptions]);

        if ($this->optionsMock instanceof Getopt) $this->output->addOptions($this->optionsMock);
    }


    /**
     * @covers \Output\VideoOutput::startOutput()
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
     * @covers \Output\VideoOutput::generateVideoFile()
     *
     * @param bool $_hasSound   Indicates whether the video will have sound or not
     */
    public function testCanCreateVideo(bool $_hasSound)
    {
        $ffmpegHelperMock = $this->getMockBuilder(\Output\Helpers\FfmpegHelper::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $ffmpegHelperMock->expects($this->exactly(1 + $_hasSound * 10))
                         ->method("executeCommand")
                         ->willReturn(false);

        $ffmpegHelperMock->expects($this->exactly(1))
                         ->method("binaryPath")
                         ->willReturn(true);

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
            $this->expectOutputRegex("/.*Gamestep: " . ($i + 1) . ".*/");
            $this->output->outputBoard($this->board);
            $this->gameLogic->calculateNextBoard($this->board);
            $this->assertTrue(file_exists($this->outputDirectory . "tmp/Frames/" . $i . ".png"));
        }

        // Check whether finishOutput creates the final gif
        $outputRegex = "\n\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n";
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
        $this->output->finishOutput();

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
     */
    public function testDetectsEmptyFramesFolder()
    {
        $this->expectOutputRegex("/.*Error: No frames in frames folder found!\n/");
        $this->output->finishOutput();
    }

    /**
     * Checks whether errors while creating the audio and the video files are detected.
     *
     * @covers \Output\VideoOutput::finishOutput()
     * @covers \Output\VideoOutput::generateVideoFile()
     */
    public function testDetectsFfmpegErrors()
    {
        $ffmpegHelperMock = $this->getMockBuilder(\Output\Helpers\FfmpegHelper::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $ffmpegHelperMock->expects($this->exactly(2))
                         ->method("binaryPath")
                         ->willReturn(true);

        $ffmpegHelperMock->expects($this->exactly(2))
                         ->method("executeCommand")
                         ->willReturn("error");

        if ($ffmpegHelperMock instanceof FfmpegHelper)
        {
            $this->output->setFfmpegHelper($ffmpegHelperMock);
            $this->output->setFrames(array(true));
            $this->output->setFillPercentages(array(2));
            $this->output->setFps(2);

            // With sound
            $this->output->setHasSound(true);

            $this->expectOutputRegex("/.*\nError while creating the audio files\. Is ffmpeg installed\?\n.*/");
            $this->output->finishOutput();

            // Without sound
            $this->output->setHasSound(false);

            $this->expectOutputRegex("/.*\nError while creating the video file\. Is ffmpeg installed\?\n/");
            $this->output->finishOutput();
        }
    }
}
