<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\FileInput;
use Ulrichsg\Getopt;
use GameOfLife\Board;
use GameOfLife\RuleSet;

/**
 * Class FileInputTest
 */
class FileInputTest extends TestCase
{
    /** @var  FileInput $input */
    private $input;

    protected function setUp()
    {
        $this->input = new FileInput();
    }

    protected function tearDown()
    {
        unset($this->input);
    }

    public function testCanAddOptions()
    {
        $options = new Getopt();
        $this->input->addOptions($options);
        $optionList = $options->getOptionList();

        $this->assertEquals(1, count($optionList));
        $this->assertContains("template", $optionList[0]);
    }

    public function testCanLoadTemplate()
    {
        $board = null;
        $board = $this->input->loadTemplate("glidergun");

        $this->assertNotEmpty($board);
        $this->assertEquals(36, $this->input->newBoardWidth());
        $this->assertEquals(20, $this->input->newBoardHeight());
    }

    public function testDetectsEmptyTemplateName()
    {
        $rulesComway = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));
        $board = new Board(10, 10, 50, true, $rulesComway);
        $options = new Getopt();

        $this->expectOutputString("Error: No template file specified\n");

        $this->input->fillBoard($board, $options);
    }
}
