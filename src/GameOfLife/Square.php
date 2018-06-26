<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores information about a square.
 */
class Square
{
	// Attributes

	/**
	 * The coordinate of the top left corner of the square
	 *
	 * @var Coordinate $topLeftCornerCoordinate
	 */
	private $topLeftCornerCoordinate;

	/**
	 * The coordinate of the bottom right corner of the square
	 *
	 * @var Coordinate $bottomRightCornerCoordinate
	 */
	private $bottomRightCornerCoordinate;


	// Magic Methods

	/**
	 * Square constructor.
	 *
	 * @param Coordinate $_topLeftCornerCoordinate The coordinate of the top left corner of the square
	 * @param Coordinate $_bottomRightCornerCoordinate The coordinate of the bottom right corner of the square
	 */
	public function __construct(Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate)
	{
		$this->topLeftCornerCoordinate = $_topLeftCornerCoordinate;
		$this->bottomRightCornerCoordinate = $_bottomRightCornerCoordinate;
	}
}
