<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\BorderPart;
use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;
use GameOfLife\Coordinate;

/**
 * Defines the shape of a border part.
 *
 * Call setParentBorderPart() before using other methods of this class
 * Call getRenderedBorderPart() to get the rendered parent border part
 */
abstract class BaseBorderPartShape
{
	// Attributes

    /**
     * The parent border part
     *
     * @var BorderPart $parentBorderPart
     */
    protected $parentBorderPart;


    // Getters and setters

	/**
	 * Sets the parent border part.
	 *
	 * @param BorderPart $_parentBorderPart The parent border part
	 */
	public function setParentBorderPart($_parentBorderPart)
	{
		$this->parentBorderPart = $_parentBorderPart;
	}


    // Class Methods

	/**
	 * Builds and returns a rendered border part of the parent border part.
	 *
	 * @param int $_fieldSize The size of a single field in pixels/symbols/etc
	 *
	 * @return RenderedBorderPart The rendered border part
	 */
	public function getRenderedBorderPart(int $_fieldSize): RenderedBorderPart
	{
		return new RenderedBorderPart(
			$this->getRawRenderedBorderPart($_fieldSize),
			$this->getBorderPartGridPositions(),
			$this->parentBorderPart
		);
	}

	/**
	 * Creates and returns the rendered parent border part.
	 *
	 * @param int $_fieldSize The size of a single field in pixels/symbols/etc
	 *
	 * @return mixed The rendered parent border part
	 */
    abstract protected function getRawRenderedBorderPart(int $_fieldSize);

	/**
	 * Calculates and returns the border part grid positions.
	 *
	 * @return Coordinate[] The border part grid positions
	 */
    abstract protected function getBorderPartGridPositions(): array;

	/**
	 * Returns whether the parent border part contains a specific coordinate.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the parent border part contains the coordinate, false otherwise
	 */
	abstract public function containsCoordinate(Coordinate $_coordinate): Bool;

	/**
	 * Returns the position at which the parent border part collides with another border part or null if there is no collision.
	 *
	 * @param BorderPart $_borderPart The other border part
	 *
	 * @return Coordinate|null The position at which the parent border part collides with the other border part or null if there is no collision
	 */
	abstract public function getCollisionPositionWith($_borderPart);
}
