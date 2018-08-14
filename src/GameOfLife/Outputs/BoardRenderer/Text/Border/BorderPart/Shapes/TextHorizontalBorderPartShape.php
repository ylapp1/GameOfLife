<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\BorderPart\Shapes;

use GameOfLife\Coordinate;
use Output\BoardRenderer\Base\Border\BorderPart\Shapes\HorizontalCollisionBorderPartShape;
use Output\BoardRenderer\Text\Border\BorderPart\TextBorderPart;
use Output\BoardRenderer\Text\Border\BorderPart\TextBorderPartCollisionPosition;
use Output\BoardRenderer\Text\Border\BorderPart\TextRenderedBorderPart;

/**
 * Shape for horizontal text border parts.
 */
class TextHorizontalBorderPartShape extends HorizontalCollisionBorderPartShape implements TextBorderPartShapeInterface
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
	 * @return TextRenderedBorderPart The rendered parent border part
	 */
    public function getRenderedBorderPart()
    {
        $borderSymbols = $this->parentBorderPart->getBorderSymbols();

	    // Using unset instead of array_shift here because array_shift changes the indexes of the array
	    $borderSymbolStart = $borderSymbols[0];
        unset($borderSymbols[0]);
        $borderSymbolEnd = array_pop($borderSymbols);

	    // Create the rendered border part
	    $renderedBorderPart = new TextRenderedBorderPart();

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
    	$collisionPosition = parent::getCollisionPositionWith($_borderPart);
    	// TODO: Add necessary information

	    $textBorderPartCollisionPosition = new TextBorderPartCollisionPosition(
	    	$collisionPosition->x(),
		    $collisionPosition->y(),
		    "",
		    "",
		    "",
		    "",
		    "",
		    "",
		    "",
		    "",
		    ""
	    );

	    return $textBorderPartCollisionPosition;
    }
}
