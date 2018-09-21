<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\Geometry;

/**
 * Stores information about a rectangle.
 */
class Rectangle
{
	// Attributes

	/**
	 * The top left corner coordinate
	 *
	 * @var Coordinate $topLeftCornerCoordinate
	 */
	private $topLeftCornerCoordinate;

	/**
	 * The bottom right corner coordinate
	 *
	 * @var Coordinate $bottomRightCornerCoordinate
	 */
	private $bottomRightCornerCoordinate;


	// Magic Methods

	/**
	 * Rectangle constructor.
	 *
	 * @param Coordinate $_cornerCoordinate The coordinate of one corner of the rectangle
	 * @param Coordinate $_opposingCornerCoordinate The coordinate of the opposing corner of the rectangle
	 */
	public function __construct(Coordinate $_cornerCoordinate, Coordinate $_opposingCornerCoordinate)
	{
		// Fetch Y top and bottom
		if ($_cornerCoordinate->y() < $_opposingCornerCoordinate->y())
		{
			$yTop = $_cornerCoordinate->y();
			$yBottom = $_opposingCornerCoordinate->y();
		}
		else
		{
			$yTop = $_opposingCornerCoordinate->y();
			$yBottom = $_cornerCoordinate->y();
		}

		// Fetch X left and right
		if ($_cornerCoordinate->x() < $_opposingCornerCoordinate->x())
		{
			$xLeft = $_cornerCoordinate->x();
			$xRight = $_opposingCornerCoordinate->x();
		}
		else
		{
			$xLeft = $_opposingCornerCoordinate->x();
			$xRight = $_cornerCoordinate->x();
		}

		$this->topLeftCornerCoordinate = new Coordinate($xLeft, $yTop);
		$this->bottomRightCornerCoordinate = new Coordinate($xRight, $yBottom);
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
