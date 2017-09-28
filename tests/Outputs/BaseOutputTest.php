<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Output\BaseOutput;
use Output\PngOutput;
use Output\VideoOutput;
use Output\GifOutput;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\BaseOutput works as expected
 */
class BaseOutputTest extends TestCase
{
    /** @var BaseOutput */
    private $output;
    /** @var Board */
    private $board;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../BaseOutputTest/";

    protected function setUp()
    {
        $this->output = new BaseOutput();
        $this->output->setOutputDirectory($this->outputDirectory);
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);
        $this->fileSystemHandler = new FileSystemHandler();
        $this->fileSystemHandler->createDirectory($this->outputDirectory);
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);
        unset($this->output);
        unset($this->board);
        unset($this->fileSystemHandler);
    }

    /**
     * @covers \Output\BaseOutput::setOutputDirectory()
     * @covers \Output\BaseOutput::outputDirectory()
     */
    public function testCanSetAttributes()
    {
        $this->output->setOutputDirectory("Hello");
        $this->assertEquals("Hello", $this->output->outputDirectory());
    }

    /**
     * @covers \Output\BaseOutput::getNewGameId()
     */
    public function testCanGetNewGameId()
    {
        $pngOutput = new PngOutput();
        $pngOutput->setOutputDirectory($this->outputDirectory);
        $gifOutput = new GifOutput();
        $gifOutput->setOutputDirectory($this->outputDirectory);
        $videoOutput = new VideoOutput();
        $videoOutput->setOutputDirectory($this->outputDirectory);

        $this->assertEquals(1, $this->output->getNewGameId("PNG"));
        $this->assertEquals(1, $this->output->getNewGameId("Gif"));
        $this->assertEquals(1, $this->output->getNewGameId("Video"));

        $pngOutput->startOutput(new Getopt(), $this->board);

        $outputRegex = "\n\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n\n";
        $outputRegex .= "Starting GIF creation. One moment please...\n";
        $outputRegex .= "GIF creation complete.";

        $this->expectOutputRegex("/.*" . $outputRegex . "*/");
        $gifOutput->startOutput(new Getopt(), $this->board);
        $gifOutput->outputBoard($this->board);
        $gifOutput->finishOutput();

        /*$videoOutput->startOutput(new Getopt(), $this->board);
        $videoOutput->outputBoard($this->board);
        $videoOutput->finishOutput();
*/
        $this->assertEquals(2, $this->output->getNewGameId("PNG"));
        $this->assertEquals(2, $this->output->getNewGameId("Gif"));
        $this->assertEquals(1, $this->output->getNewGameId("Video"));
    }
}
