<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use BoardRenderer\Image\Utils\ImageColor;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Output\Helpers\ImageColor works as expected.
 */
class ImageColorTest extends TestCase
{
    /**
     * @dataProvider colorsProvider
     *
     * @covers BoardRenderer\Image\Utils\ImageColor::__construct()
     * @covers BoardRenderer\Image\Utils\ImageColor::red()
     * @covers BoardRenderer\Image\Utils\ImageColor::green()
     * @covers BoardRenderer\Image\Utils\ImageColor::blue()
     *
     * @param int $_red     Amount red
     * @param int $_green   Amount green
     * @param int $_blue    Amount blue
     */
    public function testCanBeConstructed(int $_red, int $_green, int $_blue)
    {
        $color = new ImageColor($_red, $_green, $_blue);

        $this->assertEquals($_red, $color->red());
        $this->assertEquals($_green, $color->green());
        $this->assertEquals($_blue, $color->blue());
    }

    /**
     * @dataProvider colorsProvider
     *
     * @covers BoardRenderer\Image\Utils\ImageColor::red()
     * @covers BoardRenderer\Image\Utils\ImageColor::green()
     * @covers BoardRenderer\Image\Utils\ImageColor::blue()
     *
     * @param int $_red     Amount red
     * @param int $_green   Amount green
     * @param int $_blue    Amount blue
     *
     * @throws ReflectionException
     */
    public function testCanSetAttributes(int $_red, int $_green, int $_blue)
    {
        $color = new ImageColor (0,0,0);

        setPrivateAttribute($color, "red", $_red);
        $this->assertEquals($_red, $color->red());

        setPrivateAttribute($color, "green", $_green);
        $this->assertEquals($_green, $color->green());

        setPrivateAttribute($color, "blue", $_blue);
        $this->assertEquals($_blue, $color->blue());
    }

    public function colorsProvider()
    {
        return [
            [0, 0, 0],
            [255, 255, 255],
            [124, 124, 124],
            [23, 23, 23],
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
            [10, 20, 30],
            [40, 50, 60],
            [70, 80, 90]
        ];
    }

    /**
     * @dataProvider returnColorProvider
     *
     * @param int $_red     Amount red
     * @param int $_green   Amount green
     * @param int $_blue    Amount blue
     */
    public function testCanReturnColor(int $_red, int $_green, int $_blue)
    {
        $color = new ImageColor($_red, $_green, $_blue);
        // same image twice, so the functions won't effect each others image
        $testImageOne = imagecreate(1, 1);
        $testImageTwo = imagecreate(1, 1);

        $this->assertEquals(imagecolorallocate($testImageOne, $_red, $_green, $_blue), $color->getColor($testImageTwo));
    }

    public function returnColorProvider()
    {
        return [
            [255, 0, 0],
            [234, 23, 54],
            [123, 123, 123],
            [43, 65, 87],
            [100, 200, 255]
        ];
    }
}
