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

    protected function setUp()
    {
        $this->output = new PNGOutput();

        $rules = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $this->board = new Board(10, 10, 50, true, $rules);
    }

    protected function tearDown()
    {
        unset($this->output);
    }


    public function testCanAddOptions()
    {
        $options = new Getopt();
        $this->output->addOptions($options);
        $optionList = $options->getOptionList();

        $this->assertEquals(4, count($optionList));
        $this->assertContains("pngOutputSize", $optionList[0]);
        $this->assertContains("pngOutputCellColor", $optionList[1]);
        $this->assertContains("pngOutputBackgroundColor", $optionList[2]);
        $this->assertContains("pngOutputGridColor", $optionList[3]);
    }


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