<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\Utils;

/**
 * Stores information about a color.
 *
 * Use getColor() to get a color identifier of this color for a specific image
 */
class ImageColor
{
	// Attributes

	/**
	 * The red amount of the color (0 - 255)
	 *
	 * @var int $red
	 */
    private $red;

	/**
	 * The green amount of the color (0 - 255)
	 *
	 * @var int $green
	 */
    private $green;

	/**
	 * The blue amount of the color (0 - 255)
	 *
	 * @var int $blue
	 */
    private $blue;


    // Magic Methods

    /**
     * ImageColor constructor.
     *
     * @param int $_red The red amount of the color
     * @param int $_green The green amount of the color
     * @param int $_blue The blue amount of the color
     */
    public function __construct(int $_red, int $_green, int $_blue)
    {
        $this->red = $_red;
        $this->green = $_green;
        $this->blue = $_blue;
    }

	/**
	 * Returns whether this image color equals another image color.
	 *
	 * @param ImageColor $_imageColor The other image color
	 *
	 * @return Bool True if the colors are equal, false otherwise
	 */
    public function equals(ImageColor $_imageColor): Bool
    {
    	if ($this->red == $_imageColor->red() &&
	        $this->green == $_imageColor->green() &&
	        $this->blue == $_imageColor->blue())
	    {
	    	return true;
	    }
	    else return false;
    }


    // Getters and Setters

    /**
     * Returns the red amount of the color.
     *
     * @return int The red amount of the color
     */
    public function red(): int
    {
        return $this->red;
    }

    /**
     * Returns the green amount of the color.
     *
     * @return int The green amount of the color
     */
    public function green(): int
    {
        return $this->green;
    }

    /**
     * Returns the blue amount of the color.
     *
     * @return int The blue amount of the color
     */
    public function blue(): int
    {
        return $this->blue;
    }


    // Class Methods

    /**
     * Returns the color identifier of this color for a specific image.
     *
     * @param resource $_image The image
     *
     * @return int The color identifier of this color for the image
     */
    public function getColor($_image): int
    {
        return imagecolorallocate($_image, $this->red, $this->green, $this->blue);
    }

	/**
	 * Increases the red color amount by one.
	 * If the color is 255, 255, 255 it will be reset to 0, 0, 0.
	 */
    public function increase()
    {
    	if ($this->red < 255) $this->red++;
    	else
	    {
	    	$this->red = 0;
		    if ($this->green < 255) $this->green++;
		    else
		    {
		    	$this->green = 0;
			    if ($this->blue < 255) $this->blue++;
			    else $this->blue = 0;
		    }
	    }
    }
}
