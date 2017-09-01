<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;
use Input\BaseInput;

/**
 * Class BaseInputTest
 */
class BaseInputTest extends TestCase
{
    /**
     * @dataProvider objectOutOfBoundsProvider
     *
     * @param int $_objectPosX      X-Coordinate of top left corner of the object
     * @param int $_objectPosY      Y-Coordinate of top left corner of the object
     * @param int $_objectWidth     Object width
     * @param int $_objectHeight    Object height
     * @param int $_boardWidth      Board width
     * @param int $_boardHeight     Board height
     * @param bool $expected        Expected result
     */
    public function testDetectsObjectOutOfBounds($_objectPosX, $_objectPosY, $_objectWidth, $_objectHeight, $_boardWidth, $_boardHeight, $expected)
    {
        $input = new BaseInput();
        $input->setObjectWidth($_objectWidth);
        $input->setObjectHeight($_objectHeight);

        $this->assertEquals($expected, $input->isObjectOutOfBounds($_boardWidth, $_boardHeight, $_objectPosX, $_objectPosY));
    }

    public function objectOutOfBoundsProvider()
    {
        return [
            // Objects that are out of bounds
            "Blinker (0|0) on 0x1 board" => [0, 0, 1, 3, 0, 1, true],
            "Glider (4|4) on 6x5 board" => [4, 4, 3, 3, 6, 5, true],
            "SpaceShip (6|7) on 10x11 board" => [6, 7, 5, 4, 10, 11, true],

            // Objects within bounds
            "Blinker (2|2) on a 10x10 board" => [2, 2, 1, 3, 10, 10, false],
            "Glider (5|2) on a 20x15 board" => [5, 2, 3, 3, 20, 15, false],
            "SpaceShip (7|1) on a 100x150 board" => [7, 1, 5, 4, 100, 100, false]
        ];
    }
}