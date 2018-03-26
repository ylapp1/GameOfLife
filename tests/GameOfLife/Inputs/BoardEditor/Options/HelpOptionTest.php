<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\HelpOption;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the Help option works as expected.
 */
class HelpOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\HelpOption::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new HelpOption($boardEditor);

        $this->assertEquals("help", $option->name());
        $this->assertEquals("displayHelp", $option->callback());
        $this->assertEquals("Display help", $option->description());
        $this->assertEquals(0, $option->getNumberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether the option can exit the board editor.
     *
     * @covers \BoardEditor\Options\HelpOption::displayHelp()
     *
     * @throws \Exception
     */
    public function testCanDisplayHelp()
    {
        $boardEditor = new BoardEditor("test");
        $option = new HelpOption($boardEditor);

        $expectedOutput =  "\nSet the coordinates for the living cells as below:\n" .
                           "<X-Coordinate" . ">,<Y-Coordinate" . ">\n" .
                           "Enter the coordinates of a set field to unset it.\n" .
                           "The game starts when you type \"start\" in a new line and press <"."Enter>\n" .
                           "You can save your board configuration before starting the simulation by typing \"save\"\n" .
                           "Type \"options\" to see a list of all valid options\n" .
                           "Let's Go:\n\n";

        $this->expectOutputString($expectedOutput);

        $result = $option->displayHelp();
        $this->assertFalse($result);
    }
}
