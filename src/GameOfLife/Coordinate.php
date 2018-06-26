<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores one coordinate.
 */
class Coordinate
{
	// Attributes

	/**
	 * The X-coordinate of the field
	 *
	 * @var int $x
	 */
	private $x;

	/**
	 * The Y-coordinate of the field
	 *
	 * @var int $y
	 */
	private $y;


	// Magic Methods

	/**
	 * Coordinate constructor.
	 *
	 * @param int $_x The X-coordinate
	 * @param int $_y The Y-coordinate
	 */
	public function __construct(int $_x, int $_y)
	{
		$this->x = $_x;
		$this->y = $_y;
	}


	// Getters and Setters

	/**
	 * Returns the X-coordinate of the field.
	 *
	 * @return int The X-coordinate of the field
	 */
	public function x(): int
	{
		return $this->x;
	}

	/**
	 * Sets the X-coordinate of the field.
	 *
	 * @param int $_x The X-coordinate of the field
	 */
	public function setX(int $_x)
	{
		$this->x = $_x;
	}

	/**
	 * Returns the Y-coordinate of the field.
	 *
	 * @return int The Y-coordinate of the field
	 */
	public function y(): int
	{
		return $this->y;
	}

	/**
	 * Sets the Y-coordinate of the field.
	 *
	 * @param int $_y The Y-coordinate of the field
	 */
	public function setY(int $_y)
	{
		$this->y = $_y;
	}
}
