<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\BorderPart\Shapes;

use BoardRenderer\Base\Border\BorderPart\Shapes\BaseHorizontalBorderPartShape;
use GameOfLife\Coordinate;
use BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use BoardRenderer\Text\Border\BorderPart\TextRawRenderedBorderPart;

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
			if ($_coordinate->equals($this->parentBorderPart->startsAt())) return 0;
			elseif ($_coordinate->equals($this->parentBorderPart->endsAt())) return $this->getNumberOfBorderSymbols() + 1;
			else return $_coordinate->x() - $this->parentBorderPart->startsAt()->x();
		}
		else return null;
	}

	/**
	 * Creates and returns the rendered parent border part.
	 *
	 * @param int $_fieldSize The field size in symbols
	 *
	 * @return TextRawRenderedBorderPart The rendered parent border part
	 */
    public function getRawRenderedBorderPart(int $_fieldSize)
    {
    	// TODO: Do something with field size
	    $borderSymbols = $this->parentBorderPart->getBorderSymbols();

	    // Using unset instead of array_shift here because array_shift changes the indexes of the array
	    $borderSymbolStart = $borderSymbols[0];
	    unset($borderSymbols[0]);
	    $borderSymbolEnd = array_pop($borderSymbols);

	    // Create the rendered border part
	    $renderedBorderPart = new TextRawRenderedBorderPart();

	    $renderedBorderPart->addBorderSymbol($borderSymbolStart, new Coordinate(0, 0), false, false);
	    foreach ($borderSymbols as $x => $borderSymbol)
	    {
		    $renderedBorderPart->addBorderSymbol($borderSymbol, new Coordinate($x - 1, 0), false, true);
	    }
	    $renderedBorderPart->addBorderSymbol($borderSymbolEnd, new Coordinate($this->getNumberOfBorderSymbols(), 0), false, false);

	    return $renderedBorderPart;
    }

    public function getCollisionPositionWith($_borderPart)
    {
    	$at = parent::getCollisionPositionWith($_borderPart);

    	if ($at) return $this->getTextBorderPartCollisionPosition($at, $_borderPart);
	    else return null;
    }
}
