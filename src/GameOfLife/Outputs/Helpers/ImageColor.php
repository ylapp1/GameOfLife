<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

/**
 * Class ImageColor
 *
 * Stores a color for the image creator
 *
 * @package Output
 */
class ImageColor
{
    private $red;
    private $green;
    private $blue;

    public function __construct($_red, $_green, $_blue)
    {
        $this->red = $_red;
        $this->green = $_green;
        $this->blue = $_blue;
    }

    /**
     * @return mixed
     */
    public function red()
    {
        return $this->red;
    }

    /**
     * @param mixed $_red
     */
    public function setRed($_red)
    {
        $this->red = $_red;
    }

    /**
     * @return mixed
     */
    public function green()
    {
        return $this->green;
    }

    /**
     * @param mixed $_green
     */
    public function setGreen($_green)
    {
        $this->green = $_green;
    }

    /**
     * @return mixed
     */
    public function blue()
    {
        return $this->blue;
    }

    /**
     * @param mixed $_blue
     */
    public function setBlue($_blue)
    {
        $this->blue = $_blue;
    }


    public function getColor($_image)
    {
        return imagecolorallocate($_image, $this->red, $this->green, $this->blue);
    }
}