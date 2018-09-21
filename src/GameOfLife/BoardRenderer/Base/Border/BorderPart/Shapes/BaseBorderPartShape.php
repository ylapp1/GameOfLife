<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\BaseBorderPart;
use BoardRenderer\Base\Border\BorderPart\RenderedBorderPart;
use Util\Geometry\Coordinate;

/**
 * Defines the shape of a border part.
 *
 * Call getRenderedBorderPart() to get the rendered parent border part
 */
abstract class BaseBorderPartShape
{
	// Attributes

    /**
     * The parent border part
     *
     * @var BaseBorderPart $parentBorderPart
     */
    protected $parentBorderPart;


    // Magic Methods

	/**
	 * BaseBorderPartShape constructor.
	 *
	 * @param BaseBorderPart $_parentBorderPart The parent border part
	 */
    public function __construct($_parentBorderPart)
    {
    	$this->parentBorderPart = $_parentBorderPart;
    }


    // Class Methods

	/**
	 * Builds and returns a rendered border part from the parent border part.
	 *
	 * @param int $_fieldSize The size of a single field in pixels, symbols, etc.
	 *
	 * @return RenderedBorderPart The rendered border part
	 */
	public function getRenderedBorderPart(int $_fieldSize): RenderedBorderPart
	{
		return new RenderedBorderPart(
			$this->getRawRenderedBorderPart($_fieldSize),
			$this->getRenderedBorderPartGridPositions(),
			$this->parentBorderPart
		);
	}

	/**
	 * Creates and returns the rendered parent border part.
	 *
	 * @param int $_fieldSize The size of a single field in pixels, symbols, etc.
	 *
	 * @return mixed The raw rendered parent border part
	 */
    abstract protected function getRawRenderedBorderPart(int $_fieldSize);

	/**
	 * Calculates and returns the border part grid positions.
	 *
	 * @return Coordinate[] The border part grid positions
	 */
    abstract protected function getBorderPartGridPositions(): array;

	/**
	 * Returns the border part grid positions of the rendered border part.
	 *
	 * @return Coordinate[] The border part grid positions of the rendered border part
	 */
    protected function getRenderedBorderPartGridPositions(): array
    {
    	return $this->getBorderPartGridPositions();
    }

	/**
	 * Calculates and returns the border part grid positions at which the parent border can collide with another border part.
	 *
	 * @return Coordinate[] The possible collision positions
	 */
	protected function getPossibleCollisionPositions(): array
	{
		return $this->getBorderPartGridPositions();
	}

	/**
	 * Returns whether the parent border part contains a specific coordinate.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return Bool True if the parent border part contains the coordinate, false otherwise
	 */
	abstract public function containsCoordinate(Coordinate $_coordinate): Bool;

	/**
	 * Returns the positions at which the parent border part collides with another border part.
	 *
	 * @param BaseBorderPart $_borderPart The other border part
	 *
	 * @return Coordinate[] The positions at which the parent border part collides with the other border part
	 */
	public function getCollisionPositionsWith($_borderPart): array
	{
		$collisionPositions = array();
		foreach ($this->getPossibleCollisionPositions() as $possibleCollisionPosition)
		{
			if ($_borderPart->containsCoordinate($possibleCollisionPosition))
			{
				$collisionPositions[] = $possibleCollisionPosition;
			}
		}

		return $collisionPositions;
	}
}
