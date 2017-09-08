<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\GIFOutput;
use PHPUnit\Framework\TestCase;
use GameOfLife\Board;
use GameOfLife\RuleSet;
use Ulrichsg\Getopt;
use GameOfLife\FileSystemHandler;

/**
 * Class GIFOutputTest
 */
class GIFOutputTest extends TestCase
{
    /** @var GIFOutput $output */
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
        $this->output = new GifOutput();
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
     * @covers \Output\GIFOutput::addOptions()
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

        $this->output->addOptions($this->optionsMock);
    }


    /**
     * @covers \Output\GIFOutput::startOutput()
     */
    public function testCanCreateOutputDirectory()
    {
        $this->assertEquals(false, file_exists($this->outputDirectory));

        $this->expectOutputString("Starting GIF Output...\n");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertEquals(true, file_exists($this->outputDirectory . "Gif"));
        $this->assertEquals(true, file_exists($this->outputDirectory . "tmp/Frames"));
    }

    /**
     * @covers \Output\GIFOutput::outputBoard()
     * @covers \Output\GIFOutput::finishOutput()
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
            $this->assertEquals(true, file_exists($this->outputDirectory . "tmp/Frames/" . $i . ".gif"));
        }

        // Check whether finishOutput creates the final gif
        $outputRegex = "Simulation finished. All cells are dead or a repeating pattern was detected.\n";
        $outputRegex .= "Starting GIF creation. One moment please...\n";
        $outputRegex .= "GIF creation complete.";

        $this->expectOutputRegex("/.*". $outputRegex . ".*/");
        $this->output->finishOutput();

        $this->assertEquals(true, file_exists($this->outputDirectory . "Gif/Game_0.gif"));
        $this->assertEquals(false, file_exists($this->outputDirectory . "tmp"));
    }

    /**
     * @covers \Output\GIFOutput::finishOutput()
     */
    public function testDetectsEmptyFramesFolder()
    {
        $this->expectOutputRegex("/.*Error: No frames in frames folder found!\n/");
        $this->output->finishOutput();
    }

    /**
     * @covers \Output\GIFOutput::finishOutput()
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
