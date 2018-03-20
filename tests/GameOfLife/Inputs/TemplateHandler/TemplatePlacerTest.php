<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Board;
use GameOfLife\Field;
use TemplateHandler\Template;
use TemplateHandler\TemplatePlacer;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\TemplateHandler\TemplatePlacer works as expected.
 */
class TemplatePlacerTest extends TestCase
{
    /**
     * The test board
     *
     * @var Board $board
     */
    private $board;

    /**
     * The test fields
     *
     * @var Field[][] $fields
     */
    private $fields;

    public function setUp()
    {
        $this->board = new Board(10, 10, 1, true);

        $this->fields = array();
        $counter = 0;

        for ($y = 0; $y < 3; $y++)
        {
            $this->fields[] = array();
            for ($x = 0; $x < 3; $x++)
            {
                $field = new Field($x, $y, false);

                if ($counter < 5) $field->setValue(true);
                $counter++;

                $this->fields[$y][] = $field;
            }
        }
    }

    /**
     * Checks whether templates can be placed as expected.
     *
     * @param int $_templatePosX The x position of the top left corner of the template
     * @param int $_templatePosY The y position of the top left corner of the template
     * @param Bool $_adjustDimensions Indicates whether the board will be adjusted to have the same dimensions like the template
     * @param String $_expectedExceptionMessage The expected exception message (if any)
     *
     * @throws \Exception
     *
     * @covers \TemplateHandler\TemplatePlacer::placeTemplate()
     * @covers \TemplateHandler\TemplatePlacer::isTemplateOutOfBounds()
     *
     *
     * @dataProvider placeTemplateProvider()
     */
    public function testCanPlaceTemplate(int $_templatePosX, int $_templatePosY, Bool $_adjustDimensions, String $_expectedExceptionMessage = "")
    {
        $templatePlacer = new TemplatePlacer();

        $exceptionOccurred = false;
        try
        {
            $templatePlacer->placeTemplate($this->fields, $this->board, $_templatePosX, $_templatePosY, $_adjustDimensions);
        }
        catch (\Exception $_exception)
        {
            $exceptionOccurred = true;
            $this->assertEquals($_expectedExceptionMessage, $_exception->getMessage());
        }

        if ($_expectedExceptionMessage) $this->assertTrue($exceptionOccurred);
        else $this->assertFalse($exceptionOccurred);

        if ($_expectedExceptionMessage) $this->assertEquals(0, $this->board->getAmountCellsAlive());
        else
        {
            $this->assertEquals(5, $this->board->getAmountCellsAlive());
            $this->assertTrue($this->board->getFieldStatus(0, 0));
            $this->assertTrue($this->board->getFieldStatus(1, 0));
            $this->assertTrue($this->board->getFieldStatus(2, 0));
            $this->assertTrue($this->board->getFieldStatus(0, 1));
            $this->assertTrue($this->board->getFieldStatus(1, 1));
        }


        if ($_adjustDimensions)
        {
            $this->assertEquals(count($this->fields[0]), $this->board->width());
            $this->assertEquals(count($this->fields), $this->board->height());
        }


        // No dimensions adjustment, invalid position top left
        $board = new Board(10, 10, 1, true);
        $this->assertEquals(0, $board->getAmountCellsAlive());
    }

    public function placeTemplateProvider()
    {
        return array(
            "Adjust dimensions" => array(0, 0, true),
            "Don't adjust dimensions, valid position" => array(0, 0, false),
            "No dimension adjustment, invalid position top left" => array(-1, -1, false, "The template exceeds the left border of the board."),
            "No dimension adjustment, invalid position bottom right" => array(10, 10, false, "The template exceeds the right border of the board.")
        );
    }
}
