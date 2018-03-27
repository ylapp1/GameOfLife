<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

/**
 * Parses color selections.
 *
 * Accepts either RGB values or predefined color names (e.g. "red", "green" or "blue")
 *
 * @package Output
 */
class ColorSelector
{
    /**
     * Parses a user input color and returns the selected color as ImageColor object.
     *
     * @param String $_colorInput The user input text
     *
     * @return ImageColor The resulting color
     *
     * @throws \Exception The exception when the input color is invalid
     */
    public function getColor(string $_colorInput): ImageColor
    {
        $delimiter = "";
        if (stristr($_colorInput, ",")) $delimiter = ",";
        elseif (stristr($_colorInput, ";")) $delimiter = ";";

        if ($delimiter) $color = $this->getColorFromColorParts(explode($delimiter, $_colorInput));
        else $color = $this->getColorFromColorName($_colorInput);

        return $color;
    }

    /**
     * Returns an ImageColor from a list of color amounts.
     *
     * @param String[] $_colorAmounts The list of color amounts
     *
     * @return ImageColor
     *
     * @throws \Exception The exception when the amount of color amount parts is invalid
     */
    private function getColorFromColorParts(array $_colorAmounts): ImageColor
    {
        if (count($_colorAmounts) > 3) throw new \Exception("The color amount string may not contain more than three numbers.");
        elseif (count($_colorAmounts) < 3) throw new \Exception("The color amount string may not contain less than three numbers.");

        $amountRed = $this->getColorAmountFromString($_colorAmounts[0]);
        $amountGreen = $this->getColorAmountFromString($_colorAmounts[1]);
        $amountBlue = $this->getColorAmountFromString($_colorAmounts[2]);

        return new ImageColor($amountRed, $amountGreen, $amountBlue);
    }

    /**
     * Returns the color amount from a string.
     *
     * @param String $_colorAmountString The color amount string (must be numeric)
     *
     * @return int The color amount
     *
     * @throws \Exception The exception when the color amount string is invalid
     */
    private function getColorAmountFromString(String $_colorAmountString): int
    {
        if (! is_numeric($_colorAmountString)) throw new \Exception("The color amounts must be numbers.");

        $colorAmount = (int)$_colorAmountString;

        if ($colorAmount < 0) throw new \Exception("The color amounts may not be smaller than 0.");
        if ($colorAmount > 255) throw new \Exception("The color amounts may not be bigger than 255.");

        return $colorAmount;
    }

    /**
     * Returns the color from a color name (e.g. "red", "blue", "green").
     *
     * The color names contain the names from this list: http://www.kleines-lexikon.de/tools/pal5.html
     *
     * @param String $_colorName The color name
     *
     * @return ImageColor The corresponding image color
     *
     * @throws \Exception The exception when the color name is invalid
     */
    private function getColorFromColorName(String $_colorName): ImageColor
    {
        switch (strtolower($_colorName)) {
            case "black":
                $color = new ImageColor(0, 0, 0);
                break;
            case "gray":
                $color = new ImageColor(128, 128, 128);
                break;
            case "silver":
                $color = new ImageColor(192, 192, 192);
                break;
            case "white":
                $color = new ImageColor(255, 255, 255);
                break;
            case "navy":
                $color = new ImageColor(0, 0, 128);
                break;
            case "blue":
                $color = new ImageColor(0, 0, 255);
                break;
            case "teal":
                $color = new ImageColor(0,  128, 128);
                break;
            case "aqua":
                $color = new ImageColor(0, 255 , 255);
                break;
            case "green":
                $color = new ImageColor(0, 128, 0);
                break;
            case "lime":
                $color = new ImageColor(0, 255, 0);
                break;
            case "maroon":
                $color = new ImageColor(128, 0, 0);
                break;
            case "red":
                $color = new ImageColor(255, 0, 0);
                break;
            case "purple":
                $color = new ImageColor(128, 0, 128);
                break;
            case "fuchsia":
                $color = new ImageColor(255, 0, 255);
                break;
            case "olive":
                $color = new ImageColor(128, 128, 0);
                break;
            case "yellow":
                $color = new ImageColor(255, 255, 0);
                break;
            default:
                throw new \Exception("The color name \"" . $_colorName . "\" is invalid.");
        }

        return $color;
    }
}
