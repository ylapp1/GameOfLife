<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart;

use Util\Geometry\Coordinate;

/**
 * Stores information about a rendered border part.
 */
class RenderedBorderPart
{
	// Attributes

	/**
	 * The raw rendered border part (for example border part symbols or a border part image)
	 *
	 * @var mixed $rawRenderedBorderPart
	 */
	private $rawRenderedBorderPart;

	/**
	 * The positions on the border grid that this rendered border part fills
	 *
	 * @var Coordinate[] $borderPartGridPositions
	 */
	private $borderPartGridPositions;

	/**
	 * The parent border part of this rendered border part
	 *
	 * @var BaseBorderPart $parentBorderPart
	 */
	private $parentBorderPart;


	// Magic Methods

	/**
	 * RenderedBorderPart constructor.
	 *
	 * @param mixed $_rawRenderedBorderPart The raw rendered border part
	 * @param Coordinate[] $_borderPartGridPositions The positions on the border grid that this rendered border part fills
	 * @param BaseBorderPart $_parentBorderPart The parent border part of this rendered border part
	 */
	public function __construct($_rawRenderedBorderPart, array $_borderPartGridPositions, $_parentBorderPart)
	{
		$this->rawRenderedBorderPart = $_rawRenderedBorderPart;
		$this->borderPartGridPositions = $_borderPartGridPositions;
		$this->parentBorderPart = $_parentBorderPart;
	}


	// Getters and Setters

	/**
	 * Returns the raw rendered border part.
	 *
	 * @return mixed The raw rendered border part
	 */
	public function rawRenderedBorderPart()
	{
		return $this->rawRenderedBorderPart;
	}

	/**
	 * Returns the positions on the border grid that this rendered border part fills.
	 *
	 * @return Coordinate[] The positions on the border grid that this rendered border part fills
	 */
	public function borderPartGridPositions(): array
	{
		return $this->borderPartGridPositions;
	}

	/**
	 * Returns the parent border part of this rendered border part.
	 *
	 * @return BaseBorderPart The parent border part of this rendered border part
	 */
	public function parentBorderPart()
	{
		return $this->parentBorderPart;
	}
}
