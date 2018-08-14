<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\BorderPart;

use GameOfLife\Coordinate;

/**
 * Stores additional necessary information for text border part collision positions.
 */
class TextBorderPartCollisionPosition extends Coordinate
{
	// Attributes

	/**
	 * Indicates whether this is a center position.
	 * This distinction is necessary for the border start and end symbols
	 *
	 * @var Bool $isCenterPosition
	 */
	protected $isCenterPosition;


	// vertical collisions

	/**
	 * Indicates whether this is a collision from the top of the border symbol position
	 *
	 * @var Bool $isCollisionFromTop
	 */
	protected $isCollisionFromTop;

	/**
	 * Indicates whether this is a collision from the bottom of the border symbol position
	 *
	 * @var Bool $isCollisionFromBottom
	 */
	protected $isCollisionFromBottom;


	// horizontal collisions

	/**
	 * Indicates whether this is a collision from the left of the border symbol position
	 *
	 * @var Bool $isCollisionFromLeft
	 */
	protected $isCollisionFromLeft;

	/**
	 * Indicates whether this is a collision from the right of the border symbol position
	 *
	 * @var Bool $isCollisionFromRight
	 */
	protected $isCollisionFromRight;


	// diagonal collisions

	/**
	 * Indicates whether this is a collision from the top left of the border symbol position
	 *
	 * @var Bool $isCollisionFromTopLeft
	 */
	private $isCollisionFromTopLeft;

	/**
	 * Indicates whether this is a collision from the top right of the border symbol position
	 *
	 * @var Bool $isCollisionFromTopRight
	 */
	private $isCollisionFromTopRight;

	/**
	 * Indicates whether this is a collision from the bottom left of the border symbol position
	 *
	 * @var Bool $isCollisionFromBottomLeft
	 */
	private $isCollisionFromBottomLeft;

	/**
	 * Indicates whether this is a collision from the bottom right of the border symbol position
	 *
	 * @var $isCollisionFromBottomRight
	 */
	private $isCollisionFromBottomRight;


	// Magic Methods

	/**
	 * TextBorderPartCollisionPosition constructor.
	 *
	 * @param int $_x
	 * @param int $_y
	 * @param bool $_isCenterPosition
	 * @param bool $_isCollisionFromTop
	 * @param bool $_isCollisionFromBottom
	 * @param bool $_isCollisionFromLeft
	 * @param bool $_isCollisionFromRight
	 * @param bool $_isCollisionFromTopLeft
	 * @param bool $_isCollisionFromTopRight
	 * @param bool $_isCollisionFromBottomLeft
	 * @param bool $_isCollisionFromBottomRight
	 */
	public function __construct(int $_x, int $_y, Bool $_isCenterPosition = false, Bool $_isCollisionFromTop = false, Bool $_isCollisionFromBottom = false, Bool $_isCollisionFromLeft = false, Bool $_isCollisionFromRight = false, Bool $_isCollisionFromTopLeft = false, Bool $_isCollisionFromTopRight = false, Bool $_isCollisionFromBottomLeft = false, Bool $_isCollisionFromBottomRight = false)
	{
		parent::__construct($_x, $_y);

		$this->isCenterPosition = $_isCenterPosition;
		$this->isCollisionFromTop = $_isCollisionFromTop;
		$this->isCollisionFromBottom = $_isCollisionFromBottom;
		$this->isCollisionFromLeft = $_isCollisionFromLeft;
		$this->isCollisionFromRight = $_isCollisionFromRight;
		$this->isCollisionFromTopLeft = $_isCollisionFromTopLeft;
		$this->isCollisionFromTopRight = $_isCollisionFromTopRight;
		$this->isCollisionFromBottomLeft = $_isCollisionFromBottomLeft;
		$this->isCollisionFromBottomRight = $_isCollisionFromBottomRight;
	}


	// Getters and Setters

	public function isCenterPosition(): Bool
	{
		return $this->isCenterPosition;
	}

	public function isCollisionFromTop(): Bool
	{
		return $this->isCollisionFromTop;
	}

	public function isCollisionFromBottom(): Bool
	{
		return $this->isCollisionFromBottom;
	}

	public function isCollisionFromLeft(): Bool
	{
		return $this->isCollisionFromLeft;
	}

	public function isCollisionFromRight(): Bool
	{
		return $this->isCollisionFromRight;
	}

	public function isCollisionFromTopLeft(): Bool
	{
		return $this->isCollisionFromTopLeft;
	}

	public function isCollisionFromTopRight(): Bool
	{
		return $this->isCollisionFromTopRight;
	}

	public function isCollisionFromBottomLeft(): Bool
	{
		return $this->isCollisionFromBottomLeft;
	}

	public function isCollisionFromBottomRight(): Bool
	{
		return $this->isCollisionFromBottomRight;
	}
}
