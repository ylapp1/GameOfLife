<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseHorizontalBorderPartShape;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextBorderPartCollisionPosition;
use GameOfLife\Coordinate;

/**
 * Shape for horizontal text border parts.
 */
class TextHorizontalBorderPartShape extends BaseHorizontalBorderPartShape implements TextBorderPartShapeInterface
{
	use TextBorderPartShapeTrait;

	// Attributes

	/**
	 * The parent border part
	 *
	 * @var TextBorderPart $parentBorderPart
	 */
	protected $parentBorderPart;


    // Class Methods

	/**
	 * Calculates and returns the number of border symbols that are necessary to render the parent border part with this shape not including start and end edges.
	 *
	 * @return int The number of border symbols that are necessary to render the parent border part with this shape not including start and end edges
	 */
	public function getNumberOfBorderSymbols(): int
	{
		return $this->parentBorderPart->endsAt()->x() - $this->parentBorderPart->startsAt()->x();
	}

	/**
	 * Returns the position of a coordinate inside the list of border symbols of the parent border part.
	 *
	 * @param Coordinate $_coordinate The coordinate
	 *
	 * @return int|null The position of the coordinate inside the list of border symbols of the parent border part or null if the coordinate is not inside the parent border part
	 */
	public function getBorderSymbolPositionOf(Coordinate $_coordinate)
	{
		if ($this->containsCoordinate($_coordinate))
		{
			return ($_coordinate->x() - $this->parentBorderPart->startsAt()->x()) * 2;
		}
		else return null;
	}

	/**
	 * Calculates and returns the border part grid positions.
	 *
	 * @return Coordinate[] The border part grid positions
	 */
	public function getBorderPartGridPositions(): array
	{
		$coordinates = parent::getBorderPartGridPositions();
		$borderPartGridPositions = array();

		// Add border positions
		$borderPartGridPositions[] = new TextBorderPartGridPosition($this->parentBorderPart->startsAt(), false, false);
		foreach ($coordinates as $coordinate)
		{
			$borderPartGridPositions[] = new TextBorderPartGridPosition($coordinate, false, true);
		}
		$borderPartGridPositions[] = new TextBorderPartGridPosition($this->parentBorderPart->endsAt(), false, false);

		// Add collision positions
		$borderPartGridPositions = array_merge(
			$borderPartGridPositions,
			$this->getCollisionGridPositions($borderPartGridPositions, $this->parentBorderPart->ownCollisions())
		);


		usort($borderPartGridPositions,
			function (TextBorderPartGridPosition $_a, TextBorderPartGridPosition $_b)
			{
				if ($_a->x() > $_b->x()) return true;
				else return false;
			}
		);

		return $borderPartGridPositions;
	}

	/**
	 * Creates and returns the rendered parent border part.
	 *
	 * @param int $_fieldSize The field size in symbols
	 *
	 * @return String[] The border symbols of the parent border part
	 */
    public function getRawRenderedBorderPart(int $_fieldSize)
    {
	    return $this->parentBorderPart->getBorderSymbols();
    }

	/**
	 * Returns the positions inside the border grid where the parent border collides with another border part.
	 *
	 * @param TextBorderPart $_borderPart The other border part
	 *
	 * @return TextBorderPartCollisionPosition[] The positions
	 */
    public function getCollisionPositionsWith($_borderPart): array
    {
    	$collisionPositions = parent::getCollisionPositionsWith($_borderPart);

    	if ($collisionPositions)
	    {
		    $textBorderPartCollisionPositions = array();

		    foreach ($collisionPositions as $collisionPosition)
		    {
			    if ($this->parentBorderPart->isOuterBorderPart($_borderPart)) $inferiorBorderPart = $this->parentBorderPart;
			    else $inferiorBorderPart = $_borderPart;

			    $textBorderPartCollisionPositions[] = $this->getTextBorderPartCollisionPosition($collisionPosition, $inferiorBorderPart);
		    }

		    return $textBorderPartCollisionPositions;
	    }
	    else return array();
    }
}
