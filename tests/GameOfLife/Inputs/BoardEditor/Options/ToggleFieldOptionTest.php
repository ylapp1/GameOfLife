<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardEditor\BoardEditor;
use BoardEditor\Options\ToggleFieldOption;
use GameOfLife\Board;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether the toggle field option works as expected.
 */
class ToggleFieldOptionTest extends TestCase
{
    /**
     * Checks whether the constructor works as expected.
     *
     * @covers \BoardEditor\Options\ToggleFieldOption::__construct()
     *
     * @throws \Exception
     */
    public function testCanBeConstructed()
    {
        $boardEditor = new BoardEditor("test");
        $option = new ToggleFieldOption($boardEditor);

        $this->assertEquals("toggle", $option->name());
        $this->assertEquals("toggleField", $option->callback());
        $this->assertEquals("Toggles a field", $option->description());
        $this->assertEquals(2, $option->getNumberOfArguments());
        $this->assertEquals($boardEditor, $option->parentBoardEditor());
    }

    /**
     * Checks whether a field can be toggled by the option.
     *
     * @dataProvider toggleFieldProvider()
     * @covers \BoardEditor\Options\ToggleFieldOption::toggleField()
     *
     * @param String $_x X-Coordinate
     * @param String $_y Y-Coordinate
     * @param String $_expectedErrorMessage Expected error message
     *
     * @throws \Exception
     */
    public function testCanToggleField(String $_x, String $_y, String $_expectedErrorMessage = null)
    {
        $boardMock = $this->getMockBuilder(Board::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        $boardEditorMock = $this->getMockBuilder(BoardEditor::class)
                                ->setMethods(array("board", "outputBoard"))
                                ->disableOriginalConstructor()
                                ->getMock();

        if ($boardEditorMock instanceof BoardEditor)
        {
            $option = new ToggleFieldOption($boardEditorMock);

            if ($_expectedErrorMessage)
            {
                $amountBoardMethodCalls = 2;
            }
            else $amountBoardMethodCalls = 5;


            $boardEditorMock->expects($this->atMost($amountBoardMethodCalls))
                            ->method("board")
                            ->willReturn($boardMock);

            $boardMock->expects($this->atMost(1))
                      ->method("width")
                      ->willReturn(10);
            $boardMock->expects($this->atMost(1))
                      ->method("height")
                      ->willReturn(15);

            if ($_expectedErrorMessage == null)
            {
                $boardMock->expects($this->exactly(1))
                          ->method("getFieldState")
                          ->willReturn(false);
                $boardMock->expects($this->exactly(1))
                          ->method("setFieldState")
                          ->with((int)$_x, (int)$_y, true);

                $boardEditorMock->expects($this->exactly(1))
                                ->method("outputBoard")
                                ->willReturn(null);
            }

            $exceptionOccurred = false;

            $result = false;
            try
            {
                $result = $option->toggleField($_x, $_y);
            }
            catch (\Exception $_exception)
            {
                $exceptionOccurred = true;
                $this->assertEquals($_expectedErrorMessage, $_exception->getMessage());
            }

            if ($_expectedErrorMessage) $this->assertTrue($exceptionOccurred);
            else
            {
                $this->assertFalse($exceptionOccurred);
                $this->assertFalse($result);
            }
        }
    }

    /**
     * DataProvider for ToggleFieldOptionTest::testCanToggleField().
     *
     * @return array Test values
     */
    public function toggleFieldProvider()
    {
        return array(
            "Valid input (0|0)" => array("0", "0"),
            "X too low (-1|0)" => array("-1", "0", "The X-Position may not be smaller than 0."),
            "Y too low (0|-1)" => array("0", "-1", "The Y-Position may not be smaller than 0."),
            "X too high (11, 0)" => array("11", "0", "The X-Position may not be larger than 9."),
            "Y too high (0|16)" => array("0", "16", "The Y-Position may not be larger than 14.")
        );
    }
}
