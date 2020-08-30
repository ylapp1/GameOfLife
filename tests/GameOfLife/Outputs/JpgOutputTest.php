<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\GameLogic;
use Output\JpgOutput;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Rule\ConwayRule;
use Ulrichsg\Getopt;
use Utils\FileSystem\FileSystemWriter;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\JpgOutput works as expected.
 */
class JpgOutputTest extends TestCase
{
    /** @var JpgOutput $output */
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
        $this->output = new JpgOutput();
        $this->output->setBaseOutputDirectory($this->outputDirectory);
        $this->output->setImageOutputDirectory($this->outputDirectory . "/JPG/Game_1/");
        $this->fileSystemHandler = new FileSystemWriter();

        $this->board = new Board(10, 10, 50, true);

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
     * @covers \Output\JpgOutput::__construct()
     */
    public function testCanBeConstructed()
    {
        $output = new JpgOutput();

        $this->assertEquals("jpg", $output->optionPrefix());
        $this->assertNotEmpty(stristr($output->imageOutputDirectory(), "JPG/Game_"));
    }

    /**
     * @covers \Output\JpgOutput::fileSystemHandler()
     * @covers \Output\JpgOutput::setFileSystemHandler()
     * @covers \Output\JpgOutput::imageCreator()
     * @covers \Output\JpgOutput::setImageCreator()
     */
    public function testCanSetAttributes()
    {
        $fileSystemHandler = new FileSystemWriter();
        $colorBlack = new ImageColor(0, 0, 0);
        $imageCreator = new ImageCreator(1, 2, 3, $colorBlack, $colorBlack, $colorBlack);

        $this->output->setFileSystemHandler($fileSystemHandler);
        $this->output->setImageCreator($imageCreator);

        $this->assertEquals($fileSystemHandler,  $this->output->fileSystemHandler());
        $this->assertEquals($imageCreator, $this->output->imageCreator());
    }

    /**
     * @covers \Output\JpgOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $pngOutputOptions = array(
            array(null, "jpgOutputSize", Getopt::REQUIRED_ARGUMENT, "JpgOutput - Size of a cell in pixels"),
            array(null, "jpgOutputCellColor", Getopt::REQUIRED_ARGUMENT, "JpgOutput - Color of a cell"),
            array(null, "jpgOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "JpgOutput - Background color"),
            array(null, "jpgOutputGridColor", Getopt::REQUIRED_ARGUMENT, "JpgOutput - Grid color\n")
        );

        $this->optionsMock->expects($this->exactly(1))
            ->method("addOptions")
            ->with($pngOutputOptions);

        if ($this->optionsMock instanceof Getopt) $this->output->addOptions($this->optionsMock);
    }

    /**
     * @covers \Output\JpgOutput::startOutput()
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

        $this->expectOutputRegex("/\n *GAME OF LIFE\n *JPG OUTPUT\n\nStarting JPG Output ...\n\n/");
        $this->output->startOutput(new Getopt(), $this->board);
        $this->assertTrue(file_exists($this->outputDirectory . "JPG/Game_1"));
    }

    /**
     * @covers \Output\JpgOutput::outputBoard()
     */
    public function testCanCreatePNG()
    {
        $gameLogic = new GameLogic(new ConwayRule());
        $this->expectOutputRegex("/.*Starting simulation ...\n\n.*/");
        $this->output->startOutput(new Getopt(), $this->board);

        // Create pngs and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->expectOutputRegex("/.*Gamestep: " . ($i + 1) . ".*/");
            $this->output->outputBoard($this->board, false);
            $gameLogic->calculateNextBoard($this->board);
            $this->assertTrue(file_exists($this->outputDirectory . "JPG/Game_1/" . $i . ".jpg"));
        }
    }

    /**
     * @covers \Output\JpgOutput::finishOutput()
     * @covers \Output\BaseOutput::finishOutput()
     */
    public function testCanFinishOutput()
    {
        $this->expectOutputString("\nSimulation finished: All cells are dead.\n\n");
        $this->output->finishOutput("All cells are dead");
    }
}
