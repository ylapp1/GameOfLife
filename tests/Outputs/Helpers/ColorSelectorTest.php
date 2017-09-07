<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\Helpers\ColorSelector;
use Output\Helpers\ImageColor;
use PHPUnit\Framework\TestCase;

/**
 * Class ColorSelectorTest
 */
class ColorSelectorTest extends TestCase
{
    /**
     * @dataProvider parseColorsProvider
     * @covers \Output\Helpers\ColorSelector::getColor()
     *
     * @param string $_input        User input string
     * @param int $_expectedRed     Expected red
     * @param int $_expectedBlue    Expected blue
     * @param int $_expectedGreen   Expected green
     */
    public function testCanParseColors($_input, $_expectedRed, $_expectedGreen, $_expectedBlue)
    {
        $colorSelector = new ColorSelector();
        $color = $colorSelector->getColor($_input);

        $this->assertEquals(new ImageColor($_expectedRed, $_expectedGreen, $_expectedBlue), $color);
    }

    public function parseColorsProvider()
    {
        return [
            "100,150,200" => ["100,150,200", 100, 150, 200],
            "red" => ["red", 255, 0, 0],
            "green" => ["green", 0, 255, 0],
            "blue" => ["blue", 0, 0, 255],
            "yellow" => ["yellow", 255, 255, 0],
            "pink" => ["pink", 255, 0, 255],
            "cyan" => ["cyan", 0, 255, 255],
            "white" => ["white", 255, 255, 255],
            "invalid" => ["invalid", 0, 0, 0],
            "black" => ["black", 0, 0, 0],
            "selectcolor" => ["selectcolor", 0, 0, 0]
        ];
    }
}
