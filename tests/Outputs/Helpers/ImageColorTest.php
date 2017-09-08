<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\Helpers\ImageColor;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageColorTest
 */
class ImageColorTest extends TestCase
{
    /**
     * @dataProvider colorsProvider
     * @covers \Output\Helpers\ImageColor::__construct()
     * @covers \Output\Helpers\ImageColor::red()
     * @covers \Output\Helpers\ImageColor::green()
     * @covers \Output\Helpers\ImageColor::blue()
     *
     * @param int $_red
     * @param int $_green
     * @param int $_blue
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
     * @covers \Output\Helpers\ImageColor::setRed()
     * @covers \Output\Helpers\ImageColor::red()
     * @covers \Output\Helpers\ImageColor::setGreen()
     * @covers \Output\Helpers\ImageColor::green()
     * @covers \Output\Helpers\ImageColor::setBlue()
     * @covers \Output\Helpers\ImageColor::blue()
     *
     * @param int $_red
     * @param int $_green
     * @param int $_blue
     */
    public function testCanSetAttributes(int $_red, int $_green, int $_blue)
    {
        $color = new ImageColor (0,0,0);

        $color->setRed($_red);
        $this->assertEquals($_red, $color->red());

        $color->setGreen($_green);
        $this->assertEquals($_green, $color->green());

        $color->setBlue($_blue);
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
     * @param int $_red
     * @param int $_green
     * @param int $_blue
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
