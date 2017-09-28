<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

/**
 * Parses color selections
 *
 * Accepts either RGB values or predefined color names (e.g. "red", "green" or "blue")
 *
 * @package Output
 */
class ColorSelector
{
    /**
     * Parses a user input color and returns the selected color as ImageColor object
     *
     * @param String $_colorInput   User input
     *
     * @return ImageColor           Color
     */
    public function getColor (string $_colorInput): ImageColor
    {
        // if color input is a "R,G,B" string
        if (stristr($_colorInput, ",") != false)
        {
            $parts = explode(",", $_colorInput);

            $red = $this->validateColorAmount(intval($parts[0]));
            $green = $this->validateColorAmount(intval($parts[1]));
            $blue = $this->validateColorAmount(intval($parts[2]));

            if ($red < 0) $red = 0;

            $color = new ImageColor($red, $green, $blue);
        }
        // if color input is a color name (e.g. "red", "blue", "green")
        else
        {
            switch (strtolower($_colorInput)) {
                case "red":
                    $color = new ImageColor(255, 0, 0);
                    break;
                case "green":
                    $color = new ImageColor(0, 255, 0);
                    break;
                case "blue":
                    $color = new ImageColor(0, 0, 255);
                    break;
                case "yellow":
                    $color = new ImageColor(255, 255, 0);
                    break;
                case "pink":
                    $color = new ImageColor(255, 0, 255);
                    break;
                case "cyan":
                    $color = new ImageColor(0, 255, 255);
                    break;
                case "white":
                    $color = new ImageColor(255, 255, 255);
                    break;
                default:
                    $color = new ImageColor(0, 0, 0);
            }
        }

        return $color;
    }

    /**
     * Checks whether the color amount is less than 0 or more than 255 and adjusts the color accordingly
     *
     * @param int $_colorAmount     Amount of red, green or blue
     *
     * @return int  Fixed color if input was a invalid color
     *              Input color if input was a valid color
     */
    public function validateColorAmount(int $_colorAmount): int
    {
        $colorAmount = $_colorAmount;

        if ($_colorAmount < 0) $colorAmount = 0;
        if ($_colorAmount > 255) $colorAmount = 255;

        return $colorAmount;
    }
}