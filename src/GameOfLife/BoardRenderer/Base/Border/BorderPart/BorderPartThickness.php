<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart;

/**
 * Defines the border part thickness (height and width of each border grid position of the border part).
 */
class BorderPartThickness
{
	// Attributes

	/**
	 * The width of each border grid position of the border part
	 *
	 * @var int $width
	 */
	private $width;

	/**
	 * The height of each border grid position of the border part
	 *
	 * @var int $height
	 */
	private $height;


	// Magic Methods

	/**
	 * BorderPartThickness constructor.
	 *
	 * @param int $_width The width
	 * @param int $_height The height
	 */
	public function __construct(int $_width, int $_height)
	{
		$this->width = $_width;
		$this->height = $_height;
	}


	// Getters and Setters

	/**
	 * Returns the width.
	 *
	 * @return int The width
	 */
	public function width(): int
	{
		return $this->width;
	}

	/**
	 * Sets the width.
	 *
	 * @param int $_width The width
	 */
	public function setWidth(int $_width)
	{
		$this->width = $_width;
	}

	/**
	 * Returns the height.
	 *
	 * @return int The height
	 */
	public function height(): int
	{
		return $this->height;
	}

	/**
	 * Sets the height.
	 *
	 * @param int $_height The height
	 */
	public function setHeight(int $_height)
	{
		$this->height = $_height;
	}
}
