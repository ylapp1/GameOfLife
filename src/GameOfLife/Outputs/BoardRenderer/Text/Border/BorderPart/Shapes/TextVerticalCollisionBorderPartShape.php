<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseVerticalBorderPartShape;
use GameOfLife\Coordinate;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextRenderedBorderPart;

/**
 * Shape for vertical text border parts.
 */
class TextVerticalCollisionBorderPartShape extends BaseVerticalBorderPartShape implements TextBorderPartShapeInterface
{
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
		return $this->parentBorderPart->endsAt()->y() - $this->parentBorderPart->startsAt()->y();
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
			if ($_coordinate->equals($this->parentBorderPart->startsAt())) return 0;
			elseif ($_coordinate->equals($this->parentBorderPart->endsAt())) return $this->getNumberOfBorderSymbols() + 1;
			else return $_coordinate->y() - $this->parentBorderPart->startsAt()->y();
		}
		else return null;
	}

	/**
	 * Creates and returns the rendered parent border part.
	 *
	 * @return TextRenderedBorderPart The rendered parent border part
	 */
    public function getRawRenderedBorderPart()
    {
        $borderSymbols = $this->parentBorderPart->getBorderSymbols();

	    // Using unset instead of array_shift here because array_shift changes the indexes of the array
	    $borderSymbolStart = $borderSymbols[0];
	    unset($borderSymbols[0]);
	    $borderSymbolEnd = array_pop($borderSymbols);

	    // Create the rendered border part
	    $renderedBorderPart = new TextRenderedBorderPart();

	    $renderedBorderPart->addBorderSymbol($borderSymbolStart, new Coordinate(0, 0), false, false);
	    foreach ($borderSymbols as $y => $borderSymbol)
	    {
		    $renderedBorderPart->addBorderSymbol($borderSymbol, new Coordinate(0, $y - 1), true, false);
	    }
	    $renderedBorderPart->addBorderSymbol($borderSymbolEnd, new Coordinate(0, $this->getNumberOfBorderSymbols()), false, false);

        return $renderedBorderPart;
    }
}
