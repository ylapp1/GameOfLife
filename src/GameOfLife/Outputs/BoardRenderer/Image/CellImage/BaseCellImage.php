<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Image\CellImage;

use Output\Helpers\ImageColor;

/**
 * Parent class for cell images.
 */
abstract class BaseCellImage
{
	// Attributes

	/**
	 * The background color of the cell image
	 *
	 * @var ImageColor $backgroundColor
	 */
	protected $backgroundColor;

	/**
	 * The foreground color of the cell image
	 *
	 * @var ImageColor $foreGroundColor
	 */
	protected $foreGroundColor;

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
	 * @param ImageColor $_backgroundColor The background color of the cell image
	 * @param ImageColor $_foreGroundColor The foreground color of the cell image
	 * @param int $_height The height of the image
	 * @param int $_width The width of the image
	 */
	protected function __construct(ImageColor $_backgroundColor, ImageColor $_foreGroundColor, int $_height, int $_width)
	{
		$this->backgroundColor = $_backgroundColor;
		$this->foreGroundColor = $_foreGroundColor;
		$this->height = $_height;
		$this->width = $_width;
	}


	// Class Methods

	/**
	 * Returns the cell image.
	 *
	 * @return resource The cell image
	 */
	abstract public function getImage();
}
