<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use Input\ObjectInput;
use Ulrichsg\Getopt;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\ObjectInput works as expected
 */
class ObjectInputTest extends TestCase
{
    /** @var ObjectInput */
    private $input;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $optionsMock;

    protected function setUp()
    {
        $this->input = new ObjectInput(1, 2, "testObject");
        $this->optionsMock = $this->getMockBuilder(\Ulrichsg\Getopt::class)
                                  ->getMock();
    }

    protected function tearDown()
    {
        unset($this->input);
        unset($this->optionsMock);
    }


    /**
     * @dataProvider constructionProvider()
     * @covers \Input\ObjectInput::__construct()
     *
     * @param int $_width       Object width
     * @param int $_height      Object height
     * @param string $_name     Object name
     */
    public function testCanBeConstructed(int $_width, int $_height, string $_name)
    {
        $input = new ObjectInput($_width, $_height, $_name);

        $this->assertEquals($_width, $input->objectWidth());
        $this->assertEquals($_height, $input->objectHeight());
        $this->assertEquals($_name, $input->objectName());
    }

    public function constructionProvider()
    {
        return [
            [0, 0, "hello"],
            [1, 2, "mytest"],
            [3, 4, "thisIsATest"],
            [5, 6, "test-me"],
            [7, 8, "this test will test you"]
        ];
    }

    /**
     * @dataProvider setAttributesProvider
     * @covers \Input\ObjectInput::setObjectWidth
     * @covers \Input\ObjectInput::objectWidth
     * @covers \Input\ObjectInput::setObjectHeight
     * @covers \Input\ObjectInput::objectHeight
     * @covers \Input\ObjectInput::setObjectName()
     * @covers \Input\ObjectInput::objectName()
     *
     * @param int $_objectWidth     Object Width
     * @param int $_objectHeight    Object Height
     * @param string $_objectName   Object name
     */
    public function testCanSetAttributes($_objectWidth, $_objectHeight, $_objectName)
    {
        $this->input->setObjectWidth($_objectWidth);
        $this->assertEquals($_objectWidth, $this->input->objectWidth());

        $this->input->setObjectHeight($_objectHeight);
        $this->assertEquals($_objectHeight, $this->input->objectHeight());

        $this->input->setObjectName($_objectName);
        $this->assertEquals($_objectName, $this->input->objectName());
    }

    public function setAttributesProvider()
    {
        return [
            [10, 12, "myname"],
            [15, 275, "tetsname"],
            [203, 846, "helloMy"]
        ];
    }

    /**
     * @covers \Input\ObjectInput::addOptions()
     */
    public function testCanAddOptions()
    {
        $testObjectInput = new ObjectInput(2, 3, "myObjectName");

        $gliderOptions = array(
            array(null, "myObjectNamePosX", Getopt::REQUIRED_ARGUMENT, "X position of the myObjectName"),
            array(null, "myObjectNamePosY", Getopt::REQUIRED_ARGUMENT, "Y position of the myObjectName")
        );

        $this->optionsMock->expects($this->exactly(1))
                          ->method("addOptions")
                          ->with($gliderOptions);
        $testObjectInput->addOptions($this->optionsMock);
    }

    /**
     * @dataProvider objectOutOfBoundsProvider
     * @covers \Input\ObjectInput::isObjectOutOfBounds
     *
     * @param int $_objectPosX      X-Coordinate of top left corner of the object
     * @param int $_objectPosY      Y-Coordinate of top left corner of the object
     * @param int $_objectWidth     Object width
     * @param int $_objectHeight    Object height
     * @param int $_boardWidth      Board width
     * @param int $_boardHeight     Board height
     * @param bool $_expected       Expected result
     */
    public function testDetectsObjectOutOfBounds($_objectPosX, $_objectPosY, $_objectWidth, $_objectHeight, $_boardWidth, $_boardHeight, $_expected)
    {
        $input = new ObjectInput($_objectWidth, $_objectHeight, "testObject");

        $this->assertEquals($_expected, $input->isObjectOutOfBounds($_boardWidth, $_boardHeight, $_objectPosX, $_objectPosY));
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