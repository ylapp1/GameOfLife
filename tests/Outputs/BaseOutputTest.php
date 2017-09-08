<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\BaseOutput;
use Output\PNGOutput;
use Output\VideoOutput;
use Output\GifOutput;
use GameOfLife\Board;
use GameOfLife\RuleSet;
use Ulrichsg\Getopt;
use GameOfLife\FileSystemHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseOutputTest
 */
class BaseOutputTest extends TestCase
{
    /** @var BaseOutput */
    private $output;
    /** @var Board */
    private $board;
    /** @var FileSystemHandler */
    private $fileSystemHandler;

    protected function setUp()
    {
        $this->output = new BaseOutput();
        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);
        $this->fileSystemHandler = new FileSystemHandler();
    }

    protected function tearDown()
    {
        $this->fileSystemHandler->deleteDirectory($this->output->outputDirectory(), true);
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
        $pngOutput = new PNGOutput();
        $gifOutput = new GifOutput();
        $videoOutput = new VideoOutput();

        $this->assertEquals(0, $this->output->getNewGameId("PNG"));
        $this->assertEquals(0, $this->output->getNewGameId("Gif"));
        $this->assertEquals(0, $this->output->getNewGameId("Video"));

        $pngOutput->startOutput(new Getopt(), $this->board);


        $outputRegex = "Simulation finished. All cells are dead or a repeating pattern was detected.\n";
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
        $this->assertEquals(1, $this->output->getNewGameId("PNG"));
        $this->assertEquals(1, $this->output->getNewGameId("Gif"));
        $this->assertEquals(0, $this->output->getNewGameId("Video"));
    }
}
