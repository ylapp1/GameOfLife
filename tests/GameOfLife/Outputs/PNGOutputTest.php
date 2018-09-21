<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Simulator\Board;
use Simulator\GameLogic;
use Output\PngOutput;
use Rule\ConwayRule;
use Ulrichsg\Getopt;
use Util\FileSystem\FileSystemWriter;
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
    /** @var FileSystemWriter */
    private $fileSystemHandler;
    /** @var string */
    private $outputDirectory = __DIR__ . "/../PNGOutputTest/";

    protected function setUp()
    {
        $this->output = new PngOutput();
        $this->output->setBaseOutputDirectory($this->outputDirectory);
        $this->output->setImageOutputDirectory($this->outputDirectory . "/PNG/Game_1/");
        $this->fileSystemHandler = new FileSystemWriter();

        $this->board = new Board(10, 10, true);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();
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
    }


    /**
     * @covers \Output\PngOutput::__construct()
     */
    public function testCanBeConstructed()
    {
        $output = new PngOutput();

        $this->assertEquals("png", $output->optionPrefix());
        $this->assertNotEmpty(stristr($output->imageOutputDirectory(), "PNG/GAME_"));
    }

    /**
     * @covers \Output\PngOutput::fileSystemHandler()
     * @covers \Output\PngOutput::setFileSystemHandler()
     */
    public function testCanSetAttributes()
    {
        $fileSystemHandler = new FileSystemWriter();

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->assertEquals($fileSystemHandler,  $this->output->fileSystemHandler());
    }

    /**
     * @covers \Output\PngOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $pngOutputOptions = array(
            array(null, "pngOutputSize", Getopt::REQUIRED_ARGUMENT, "PngOutput - Size of a cell in pixels"),
            array(null, "pngOutputCellColor", Getopt::REQUIRED_ARGUMENT, "PngOutput - Color of a cell"),
            array(null, "pngOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "PngOutput - Background color"),
            array(null, "pngOutputGridColor", Getopt::REQUIRED_ARGUMENT, "PngOutput - Grid color\n")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($pngOutputOptions);

        if ($this->optionsMock instanceof Getopt) $this->output->addOptions($this->optionsMock);
    }

    /**
     * @covers \Output\PngOutput::startOutput()
     *
     * @throws \Exception
     */
    public function testCanCreateOutputDirectory()
    {
        try
        {
            $this->fileSystemHandler->deleteDirectory($this->outputDirectory, true);
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }
        $this->assertFalse(file_exists($this->outputDirectory));

        $this->expectOutputRegex("/\n *GAME OF LIFE\n *PNG OUTPUT\n*Starting PNG Output ...\n\n/");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertTrue(file_exists($this->outputDirectory . "PNG/Game_1"));
    }

    /**
     * @covers \Output\PngOutput::outputBoard()
     *
     * @throws \Exception
     */
    public function testCanCreatePNG()
    {
        $gameLogic = new GameLogic(new ConwayRule(), 1);
        $this->expectOutputRegex("/.*Starting simulation ...\n\n.*/");
        $this->output->startOutput(new Getopt(), $this->board);

        // Create pngs and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->expectOutputRegex("/.*Gamestep: " . $gameLogic->gameStep() . ".*/");
            $this->output->outputBoard($this->board, $gameLogic->gameStep());
            $gameLogic->calculateNextBoard($this->board);
            $this->assertTrue(file_exists($this->outputDirectory . "PNG/Game_1/" . ($gameLogic->gameStep() - 1) . ".png"));
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
