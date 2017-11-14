<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

/**
 * Stores a color for image creator.
 *
 * @package Output
 */
class ImageColor
{
    private $red;
    private $green;
    private $blue;

    /**
     * ImageColor constructor.
     *
     * @param int $_red     Amount Red of the color (0-255)
     * @param int $_green   Amount Green of the color (0-255)
     * @param int $_blue    Amount Blue of the color (0-255)
     */
    public function __construct(int $_red, int $_green, int $_blue)
    {
        $this->red = $_red;
        $this->green = $_green;
        $this->blue = $_blue;
    }

    /**
     * Returns the amount red of the color.
     *
     * @return int          Amount red of the color
     */
    public function red(): int
    {
        return $this->red;
    }

    /**
     * Sets the amount red of the color.
     *
     * @param int $_red     Amount red of the color
     */
    public function setRed(int $_red)
    {
        $this->red = $_red;
    }

    /**
     * Returns the amount green of the color.
     *
     * @return int      Amount green of the color
     */
    public function green(): int
    {
        return $this->green;
    }

    /**
     * Sets the amount green of the color.
     *
     * @param int $_green   Amount green of the color
     */
    public function setGreen(int $_green)
    {
        $this->green = $_green;
    }

    /**
     * Returns the amount blue of the color.
     *
     * @return int      Amount blue of the color
     */
    public function blue(): int
    {
        return $this->blue;
    }

    /**
     * Sets the amount blue of the color.
     *
     * @param int $_blue    Amount blue of the color
     */
    public function setBlue(int $_blue)
    {
        $this->blue = $_blue;
    }

    /**
     * Returns a color that can be used only on a specific image.
     *
     * @param resource $_image  The image on which the color will be used
     *
     * @return int  The image color id
     */
    public function getColor($_image): int
    {
        return imagecolorallocate($_image, $this->red, $this->green, $this->blue);
    }
}