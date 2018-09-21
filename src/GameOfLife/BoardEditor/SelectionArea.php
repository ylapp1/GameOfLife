<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor;

use Util\Geometry\Coordinate;

/**
 * Stores information about a two dimensional selection area.
 * TODO: Remove this class, use Rectangle instead
 */
class SelectionArea
{
	// Attributes

	/**
	 * The coordinate of the top left corner of the selection area
	 *
	 * @var Coordinate $topLeftCornerCoordinate
	 */
	private $topLeftCornerCoordinate;

	/**
	 * The coordinate of the bottom right corner of the selection area
	 *
	 * @var Coordinate $bottomRightCornerCoordinate
	 */
	private $bottomRightCornerCoordinate;


	// Magic Methods

	/**
	 * SelectionArea constructor.
	 *
	 * @param Coordinate $_topLeftCornerCoordinate The coordinate of the top left corner of the square
	 * @param Coordinate $_bottomRightCornerCoordinate The coordinate of the bottom right corner of the square
	 */
	public function __construct(Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate)
	{
		if ($_topLeftCornerCoordinate->x() <= $_bottomRightCornerCoordinate->x())
		{
			$xCoordinateLeft = $_topLeftCornerCoordinate->x();
			$xCoordinateRight = $_bottomRightCornerCoordinate->x();
		}
		else
		{
			$xCoordinateLeft = $_bottomRightCornerCoordinate->x();
			$xCoordinateRight = $_topLeftCornerCoordinate->x();
		}

		if ($_topLeftCornerCoordinate->y() <= $_bottomRightCornerCoordinate->y())
		{
			$yCoordinateTop = $_topLeftCornerCoordinate->y();
			$yCoordinateBottom = $_bottomRightCornerCoordinate->y();
		}
		else
		{
			$yCoordinateTop = $_bottomRightCornerCoordinate->y();
			$yCoordinateBottom = $_topLeftCornerCoordinate->y();
		}

		$this->topLeftCornerCoordinate = new Coordinate($xCoordinateLeft, $yCoordinateTop);
		$this->bottomRightCornerCoordinate = new Coordinate($xCoordinateRight, $yCoordinateBottom);
	}


	// Getters and Setters

	/**
	 * Returns the coordinate of the top left corner of the selection area.
	 *
	 * @return Coordinate The coordinate of the top left corner of the selection area
	 */
	public function topLeftCornerCoordinate(): Coordinate
	{
		return $this->topLeftCornerCoordinate;
	}

	/**
	 * Sets the coordinate of the top left corner of the selection area.
	 *
	 * @param Coordinate $_topLeftCornerCoordinate The coordinate of the top left corner of the selection area
	 */
	public function setTopLeftCornerCoordinate(Coordinate $_topLeftCornerCoordinate): void
	{
		$this->topLeftCornerCoordinate = $_topLeftCornerCoordinate;
	}

	/**
	 * Returns the coordinate of the bottom right corner of the selection area.
	 *
	 * @return Coordinate The coordinate of the bottom right corner of the selection area
	 */
	public function bottomRightCornerCoordinate(): Coordinate
	{
		return $this->bottomRightCornerCoordinate;
	}

	/**
	 * Sets the coordinate of the bottom right corner of the selection area.
	 *
	 * @param Coordinate $_bottomRightCornerCoordinate The coordinate of the bottom right corner of the selection area
	 */
	public function setBottomRightCornerCoordinate(Coordinate $_bottomRightCornerCoordinate): void
	{
		$this->bottomRightCornerCoordinate = $_bottomRightCornerCoordinate;
	}
}
