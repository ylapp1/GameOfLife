<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores information about a rectangle.
 */
class Rectangle
{
	/**
	 * The top left corner coordinate
	 *
	 * @var Coordinate $topLeftCornerCoordinate
	 */
	protected $topLeftCornerCoordinate;

	/**
	 * The bottom right corner coordinate
	 *
	 * @var Coordinate $bottomRightCornerCoordinate
	 */
	protected $bottomRightCornerCoordinate;


	// Magic Methods

	/**
	 * Rectangle constructor.
	 *
	 * @param Coordinate $_topLeftCornerCoordinate The top left corner coordinate
	 * @param Coordinate $_bottomRightCornerCoordinate The bottom right corner coordinate
	 */
	public function __construct(Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate)
	{
		$this->topLeftCornerCoordinate = $_topLeftCornerCoordinate;
		$this->bottomRightCornerCoordinate = $_bottomRightCornerCoordinate;
	}


	// Getters and Setters

	/**
	 * Returns the top left corner coordinate.
	 *
	 * @return Coordinate The top left corner coordinate
	 */
	public function topLeftCornerCoordinate(): Coordinate
	{
		return $this->topLeftCornerCoordinate;
	}

	/**
	 * Returns the bottom right corner coordinate.
	 *
	 * @return Coordinate The bottom right corner coordinate
	 */
	public function bottomRightCornerCoordinate(): Coordinate
	{
		return $this->bottomRightCornerCoordinate;
	}
}
