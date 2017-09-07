<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\RuleSet;
use Output\PNGOutput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

/**
 * Class PNGOutputTest
 */
class PNGOutputTest extends TestCase
{
    /** @var PNGOutput $output */
    private $output;
    /** @var Board $board */
    private $board;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    protected function setUp()
    {
        $this->output = new PNGOutput();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);

        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();
    }

    protected function tearDown()
    {
        unset($this->output);
        unset($this->board);
        unset($this->optionsMock);
    }


    /**
     * @covers \Output\PNGOutput::addOptions()
     */
    public function testCanAddOptions()
    {
        $pngOutputOptions = array(
            array(null, "pngOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for PNG outputs"),
            array(null, "pngOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for PNG outputs"),
            array(null, "pngOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Color of the background for PNG outputs"),
            array(null, "pngOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Color of the grid for PNG outputs")
        );

        $this->optionsMock->expects($this->exactly(1))
            ->method("addOptions")
            ->with($pngOutputOptions);

        $this->output->addOptions($this->optionsMock);
    }


    /**
     * @covers \Output\PNGOutput::startOutput()
     */
    public function testCanCreateOutputDirectory()
    {
        // Delete output directory if exists
        if (file_exists(__DIR__ . "/../../Output"))
        {
            $this->deleteDir(__DIR__ . "/../../Output");
        }

        $this->expectOutputString("Starting simulation ...\n\n");
        $this->output->startOutput(new Getopt(), $this->board);

        $this->assertEquals(true, file_exists(__DIR__ . "/../../Output/PNG/Game_1"));
        $this->deleteDir(__DIR__ . "/../../Output");
    }

    private function deleteDir($dirPath)
    {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') $dirPath .= '/';

        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file)
        {
            if (is_dir($file)) self::deleteDir($file);
            else unlink($file);
        }

        if (file_exists($dirPath)) rmdir($dirPath);
    }


    /**
     * @covers \Output\PNGOutput::outputBoard()
     */
    public function testCanCreatePNG()
    {
        $this->deleteDir(__DIR__ . "/../../Output");

        $this->expectOutputRegex("/.*Starting simulation ...\n\n.*/");
        $this->output->startOutput(new Getopt(), $this->board);

        // Create pngs and check whether the files are created
        for ($i = 0; $i < 10; $i++)
        {
            $this->output->outputBoard($this->board);
            $this->board->calculateStep();
            $this->assertEquals(true, file_exists(__DIR__ . "/../../Output/PNG/Game_1/" . $i . ".png"));
        }

        $this->deleteDir(__DIR__ . "/../../Output");
    }
}