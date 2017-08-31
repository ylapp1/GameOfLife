<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use PHPUnit\Framework\TestCase;

/**
 * Class dummyTest
 */
class dummyTest extends TestCase
{
    public function testOutput()
    {
        $this->expectOutputString("test");
        echo "test";
    }
}