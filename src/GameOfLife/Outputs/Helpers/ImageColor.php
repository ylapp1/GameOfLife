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

    public function __construct(int $_red, int $_green, int $_blue)
    {
        $this->red = $_red;
        $this->green = $_green;
        $this->blue = $_blue;
    }

    /**
     * @return int
     */
    public function red()
    {
        return $this->red;
    }

    /**
     * @param int $_red
     */
    public function setRed(int $_red)
    {
        $this->red = $_red;
    }

    /**
     * @return int
     */
    public function green()
    {
        return $this->green;
    }

    /**
     * @param int $_green
     */
    public function setGreen(int $_green)
    {
        $this->green = $_green;
    }

    /**
     * @return int
     */
    public function blue()
    {
        return $this->blue;
    }

    /**
     * @param int $_blue
     */
    public function setBlue(int $_blue)
    {
        $this->blue = $_blue;
    }

    /**
     * Returns a color that can be used only on a specific image
     *
     * @param resource $_image
     * @return int
     */
    public function getColor($_image)
    {
        return imagecolorallocate($_image, $this->red, $this->green, $this->blue);
    }
}