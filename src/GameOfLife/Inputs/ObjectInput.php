<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input;

use Ulrichsg\Getopt;

/**
 * Parent class for classes that place an object (e.g. blinker, glider, spaceship) on the board.
 */
class ObjectInput extends BaseInput
{
    /**
     * Object height
     *
     * @var int $objectHeight
     */
    private $objectHeight;

    /**
     * Object width
     *
     * @var int $objectWidth
     */
    private $objectWidth;

    /**
     * Object name (used to generate options)
     *
     * @var string $objectName
     */
    private $objectName;


    /**
     * ObjectInput constructor.
     *
     * @param int $_objectWidth Object width
     * @param int $_objectHeight Object height
     * @param string $_objectName Object name
     */
    public function __construct(int $_objectWidth, int $_objectHeight, string $_objectName)
    {
        $this->objectWidth = $_objectWidth;
        $this->objectHeight = $_objectHeight;
        $this->objectName = $_objectName;
    }


    // Getters and Setters

    /**
     * Returns the object height.
     *
     * @return int Object height
     */
    public function objectHeight(): int
    {
        return $this->objectHeight;
    }

    /**
     * Sets the object height.
     *
     * @param int $_objectHeight Object height
     */
    public function setObjectHeight(int $_objectHeight)
    {
        $this->objectHeight = $_objectHeight;
    }

    /**
     * Returns the object width.
     *
     * @return int Object width
     */
    public function objectWidth(): int
    {
        return $this->objectWidth;
    }

    /**
     * Sets the object width.
     *
     * @param int $_objectWidth Object width
     */
    public function setObjectWidth(int $_objectWidth)
    {
        $this->objectWidth = $_objectWidth;
    }

    /**
     * Returns the object name.
     *
     * @return string Object name
     */
    public function objectName(): string
    {
        return $this->objectName;
    }

    /**
     * Sets the object name.
     *
     * @param string $_objectName Object name
     */
    public function setObjectName(string $_objectName)
    {
        $this->objectName = $_objectName;
    }


    /**
     * Adds the object specific options (X- and Y-Position) to the option list.
     *
     * Uses the objectName attribute to generate a option name and description
     *
     * @param Getopt $_options Option list to which the objects options are added
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array
            (
                array(null, $this->objectName . "PosX", Getopt::REQUIRED_ARGUMENT, "X position of the " . $this->objectName),
                array(null, $this->objectName . "PosY", Getopt::REQUIRED_ARGUMENT, "Y position of the " . $this->objectName)
            )
        );
    }

    /**
     * Checks whether the object is out of bounds.
     *
     * Uses the class attributes "objectWidth" and "objectHeight" to calculate the object dimensions
     *
     * @param int $_boardWidth Board width
     * @param int $_boardHeight Board height
     * @param int $_posX X-Coordinate of the top left border of the object
     * @param int $_posY Y-Coordinate of the top left border of the object
     *
     * @return bool True: Object is out of bounds
     *              False: Object is not out of bounds
     */
    public function isObjectOutOfBounds(int $_boardWidth, int $_boardHeight, int $_posX, int $_posY): bool
    {
        if ($_posX < 0 ||
            $_posY < 0 ||
            $_posX + $this->objectWidth > $_boardWidth ||
            $_posY + $this->objectHeight > $_boardHeight)
        {
            return true;
        }
        else return false;
    }
}