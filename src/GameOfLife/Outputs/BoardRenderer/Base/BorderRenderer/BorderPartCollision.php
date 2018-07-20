<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base;

/**
 * Stores information about a border part collision.
 */
class BorderPartCollision
{
	/**
	 * The position of the collision inside the border relative from the start position of the border
	 *
	 * @var int $position
	 */
	private $position;

	/**
	 * The reference to the colliding border part
	 *
	 * @var BaseBorderPart $with
	 */
	private $with;

	/**
	 * Indicates whether the colliding border is an outer border
	 *
	 * @var Bool $isOuterBorderCollision
	 */
	private $isOuterBorderCollision;


	/**
	 * BorderPartCollision constructor.
	 *
	 * @param int $_position The position of the collision inside the border relative from the start position of the border
	 * @param BaseBorderPart $_with The reference to the border that collides
	 * @param bool $_isOuterBorderCollision Indicates whether the colliding border is an outer border
	 */
	public function __construct(int $_position, $_with, Bool $_isOuterBorderCollision)
	{
		$this->position = $_position;
		$this->with = $_with;
		$this->isOuterBorderCollision = $_isOuterBorderCollision;
	}
}