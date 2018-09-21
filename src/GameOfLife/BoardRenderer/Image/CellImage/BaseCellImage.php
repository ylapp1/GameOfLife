<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Image\CellImage;

use BoardRenderer\Image\Utils\ImageColor;

/**
 * Parent class for cell images.
 */
abstract class BaseCellImage
{
	// Attributes

	/**
	 * The color of the cell image
	 *
	 * @var ImageColor $backgroundColor
	 */
	protected $color;

	/**
	 * The height of the image
	 *
	 * @var int $height
	 */
	protected $height;

	/**
	 * The width of the image
	 *
	 * @var int $width
	 */
	protected $width;


	// Magic Methods

	/**
	 * BaseCellImage constructor.
	 *
	 * @param ImageColor $_color The color of the cell image
	 * @param int $_height The height of the image
	 * @param int $_width The width of the image
	 */
	public function __construct(ImageColor $_color, int $_height, int $_width)
	{
		$this->color = $_color;
		$this->height = $_height;
		$this->width = $_width;
	}


	// Class Methods

	/**
	 * Creates and returns the cell image.
	 *
	 * @return resource The cell image
	 */
	abstract public function getImage();
}
