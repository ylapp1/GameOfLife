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
     * Checks whether templates can be placed as expected.
     *
     * @covers \TemplateHandler\TemplatePlacer::placeTemplate()
     * @covers \TemplateHandler\TemplatePlacer::isTemplateOutOfBounds()
     */
    public function testCanPlaceTemplate()
    {
        $board = new Board(10, 10, 1, true);

        $fields = array();
        $counter = 0;

        for ($y = 0; $y < 3; $y++)
        {
            $fields[] = array();
            for ($x = 0; $x < 3; $x++)
            {
                $field = new Field($board, $x, $y);

                if ($counter < 5) $field->setValue(true);
                $counter++;

                $fields[$y][] = $field;
            }
        }

        $templatePlacer = new TemplatePlacer();
        $template = new Template($fields);

        // Dimensions adjustment test
        $result = $templatePlacer->placeTemplate($template, $board, 0, 0, true);

        $this->assertTrue($result);
        $this->assertEquals(5, $board->getAmountCellsAlive());
        $this->assertTrue($board->getFieldStatus(0, 0));
        $this->assertTrue($board->getFieldStatus(1, 0));
        $this->assertTrue($board->getFieldStatus(2, 0));
        $this->assertTrue($board->getFieldStatus(0, 1));
        $this->assertTrue($board->getFieldStatus(1, 1));
        $this->assertEquals($template->width(), $board->width());
        $this->assertEquals($template->height(), $board->height());


        // No dimensions adjustment, valid position
        $board = new Board(10, 10, 1, true);
        $this->assertEquals(0, $board->getAmountCellsAlive());
        $result = $templatePlacer->placeTemplate($template, $board, 0, 0, false);

        $this->assertTrue($result);
        $this->assertEquals(5, $board->getAmountCellsAlive());
        $this->assertTrue($board->getFieldStatus(0, 0));
        $this->assertTrue($board->getFieldStatus(1, 0));
        $this->assertTrue($board->getFieldStatus(2, 0));
        $this->assertTrue($board->getFieldStatus(0, 1));
        $this->assertTrue($board->getFieldStatus(1, 1));
        $this->assertEquals(10, $board->width());
        $this->assertEquals(10, $board->height());


        // No dimensions adjustment, invalid position top left
        $board = new Board(10, 10, 1, true);
        $this->assertEquals(0, $board->getAmountCellsAlive());
        $result = $templatePlacer->placeTemplate($template, $board, -1, -1, false);

        $this->assertFalse($result);
        $this->assertEquals(0, $board->getAmountCellsAlive());


        // No dimensions adjustment, invalid position bottom right
        $board = new Board(10, 10, 1, true);
        $this->assertEquals(0, $board->getAmountCellsAlive());
        $result = $templatePlacer->placeTemplate($template, $board, 10, 10, false);

        $this->assertFalse($result);
        $this->assertEquals(0, $board->getAmountCellsAlive());
    }
}
