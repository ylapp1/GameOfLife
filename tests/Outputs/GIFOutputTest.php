<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Output\GIFOutput;
use PHPUnit\Framework\TestCase;

/**
 * Class GIFOutputTest
 */
class GIFOutputTest extends TestCase
{
    public function testCanOutputOne()
    {
        $this->expectOutputString("1");
        echo "1";
    }
}
