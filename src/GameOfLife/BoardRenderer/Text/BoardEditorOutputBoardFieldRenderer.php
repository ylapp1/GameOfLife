<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text;

use Simulator\Field;
use Utils\Geometry\Coordinate;

/**
 * Renders the board fields for the board editor output.
 * This class has separate render symbols for a highlighted cell.
 */
class BoardEditorOutputBoardFieldRenderer extends TextBoardFieldRenderer
{
	// Attributes

	/**
	 * The symbol that will be used to render a highlighted alive cell
	 *
	 * @var String $highLightCellAliveSymbol
	 */
	private $highLightCellAliveSymbol;

	/**
	 * The symbol that will be used to render a highlighted dead cell
	 *
	 * @var String $highLightCellDeadSymbol
	 */
	private $highLightCellDeadSymbol;

	/**
	 * The coordinate of the currently highlighted field
	 *
	 * @var Coordinate $highLightFieldCoordinate
	 */
	private $highLightFieldCoordinate;


	// Magic Methods

	/**
	 * BoardEditorOutputBoardFieldRenderer constructor.
	 *
	 * @param String $_cellAliveSymbol The symbol that is used to print a living cell
	 * @param String $_cellDeadSymbol The symbol that is used to print a dead cell
	 * @param String $_highLightCellAliveSymbol The symbol that is used to render a highlighted alive cell
	 * @param String $_highLightCellDeadSymbol The symbol that is used to render a highlighted dead cell
	 */
	public function __construct(String $_cellAliveSymbol = null, String $_cellDeadSymbol = null, String $_highLightCellAliveSymbol = null, String $_highLightCellDeadSymbol = null)
	{
		parent::__construct($_cellAliveSymbol, $_cellDeadSymbol);
		$this->highLightCellAliveSymbol = $_highLightCellAliveSymbol;
		$this->highLightCellDeadSymbol = $_highLightCellDeadSymbol;
	}


	// Getters and Setters

	/**
	 * Sets the highlight field coordinate.
	 *
	 * @param Coordinate $_highLightFieldCoordinate The highlight field coordinate
	 */
	public function setHighLightFieldCoordinate(Coordinate $_highLightFieldCoordinate)
	{
		$this->highLightFieldCoordinate = $_highLightFieldCoordinate;
	}


	// Class Methods

	/**
	 * Resets the highlight field coordinate.
	 */
	public function reset()
	{
		$this->highLightFieldCoordinate = null;
	}

	/**
	 * Renders a two dimensional list of board fields and returns the list of rendered board fields.
	 *
	 * @param Field[][] $_boardFields The board fields
	 *
	 * @return String[][] The list of rendered board fields
	 */
	public function getRenderedBoardFields($_boardFields): array
	{
		$renderedBoardFields = parent::getRenderedBoardFields($_boardFields);

		if ($this->highLightFieldCoordinate)
		{
			$highLightX = $this->highLightFieldCoordinate->x();
			$highLightY = $this->highLightFieldCoordinate->y();

			$highLightBoardField = $_boardFields[$highLightY][$highLightX];
			if ($highLightBoardField->isAlive()) $highLightCellSymbol = $this->highLightCellAliveSymbol;
			else $highLightCellSymbol = $this->highLightCellDeadSymbol;

			$renderedBoardFields[$highLightY][$highLightX] = $highLightCellSymbol;
		}

		return $renderedBoardFields;
	}
}
