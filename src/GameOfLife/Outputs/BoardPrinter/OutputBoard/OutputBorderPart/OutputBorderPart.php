<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\OutputBorderPart;

use GameOfLife\Coordinate;
use Output\BoardPrinter\Border\InnerBorder\BaseInnerBorderPartBuilder;
use Output\BoardPrinter\Border\OuterBorder\BaseOuterBorderPartBuilder;
use Output\BoardPrinter\OutputBoard\SymbolGrid\BorderSymbolGrid;

/**
 * Container that stores the information about a part of a border.
 */
abstract class OutputBorderPart
{
	// Attributes

	/**
	 * The symbol for the start of the border
	 *
	 * @var String $borderSymbolStart
	 */
	protected $borderSymbolStart;

	/**
	 * The symbol for the center parts of the border
	 *
	 * @var String $borderSymbolCenter
	 */
	protected $borderSymbolCenter;

	/**
	 * The symbol for the end of the border
	 *
	 * @var String $borderSymbolEnd
	 */
	protected $borderSymbolEnd;

	/**
	 * The symbol for the start of the border when the start collides with an outer border
	 *
	 * @var String $borderSymbolOuterBorderCollisionStart
	 */
    private $borderSymbolOuterBorderCollisionStart;

	/**
	 * The symbol for the center parts of the border when a center part collides with an outer border
	 *
	 * @var String $borderSymbolOuterBorderCollisionCenter
	 */
    private $borderSymbolOuterBorderCollisionCenter;

	/**
	 * The symbol for the end of the border when the end collides with an outer border
	 *
	 * @var String $borderSymbolOuterBorderCollisionEnd
	 */
    private $borderSymbolOuterBorderCollisionEnd;

	/**
	 * The symbol for the start of the border when the start collides with an inner border
	 *
	 * @var String $borderSymbolInnerBorderCollisionStart
	 */
	private $borderSymbolInnerBorderCollisionStart;

	/**
	 * The symbol for the center parts of the border when a center part collides with an inner border
	 *
	 * @var String $borderSymbolInnerBorderCollisionCenter
	 */
	private $borderSymbolInnerBorderCollisionCenter;

	/**
	 * The symbol for the end of the border when the end collides with an inner border
	 *
	 * @var String $borderSymbolInnerBorderCollisionEnd
	 */
	private $borderSymbolInnerBorderCollisionEnd;

	/**
	 * The border symbols that currently represent the border
	 * These will be changed on collision detections
	 *
	 * @var String[] $borderSymbols
	 */
	protected $borderSymbols;

	/**
	 * The start coordinate of this border
	 *
	 * @var Coordinate $startsAt
	 */
	protected $startsAt;

	/**
	 * The end coordinate of this border
	 *
	 * @var Coordinate $endsAt
	 */
	protected $endsAt;


	// Magic Methods

	/**
	 * OutputBorderPart constructor.
	 *
	 * @param Coordinate $_startsAt The start coordinate of this border
	 * @param Coordinate $_endsAt The end coordinate of this border
	 * @param String $_borderSymbolStart The symbol for the start of the border
	 * @param String $_borderSymbolCenter The symbol for the center parts of the border
	 * @param String $_borderSymbolEnd The symbol for the end of the border
	 * @param String $_borderSymbolOuterBorderCollisionStart The symbol for the start of the border when the start collides with an outer border
	 * @param String $_borderSymbolOuterBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an outer border
	 * @param String $_borderSymbolOuterBorderCollisionEnd The symbol for the end of the border when the end collides with an outer border
	 * @param String $_borderSymbolInnerBorderCollisionStart The symbol for the start of the border when the start collides with an inner border
	 * @param String $_borderSymbolInnerBorderCollisionCenter The symbol for the center parts of the border when a center part collides with an inner border
	 * @param String $_borderSymbolInnerBorderCollisionEnd The symbol for the end of the border when the end collides with an inner border
	 */
	protected function __construct(Coordinate $_startsAt, Coordinate $_endsAt, String $_borderSymbolStart, String $_borderSymbolCenter, String $_borderSymbolEnd, String $_borderSymbolOuterBorderCollisionStart, String $_borderSymbolOuterBorderCollisionCenter, String $_borderSymbolOuterBorderCollisionEnd, String $_borderSymbolInnerBorderCollisionStart, String $_borderSymbolInnerBorderCollisionCenter, String $_borderSymbolInnerBorderCollisionEnd)
    {
    	$this->startsAt = $_startsAt;
    	$this->endsAt = $_endsAt;

    	$this->borderSymbolStart = $_borderSymbolStart;
    	$this->borderSymbolCenter = $_borderSymbolCenter;
    	$this->borderSymbolEnd = $_borderSymbolEnd;
    	$this->borderSymbolOuterBorderCollisionStart = $_borderSymbolOuterBorderCollisionStart;
    	$this->borderSymbolOuterBorderCollisionCenter = $_borderSymbolOuterBorderCollisionCenter;
    	$this->borderSymbolOuterBorderCollisionEnd = $_borderSymbolOuterBorderCollisionEnd;
    	$this->borderSymbolInnerBorderCollisionStart = $_borderSymbolInnerBorderCollisionStart;
    	$this->borderSymbolInnerBorderCollisionCenter = $_borderSymbolInnerBorderCollisionCenter;
    	$this->borderSymbolInnerBorderCollisionEnd = $_borderSymbolInnerBorderCollisionEnd;

    	$this->initializeBorderSymbols();
    }


    // Getters and Setters

	/**
	 * Returns the symbol for the start of the border.
	 *
	 * @return String The symbol for the start of the border
	 */
	public function borderSymbolStart(): String
	{
		return $this->borderSymbolStart;
	}

	/**
	 * Returns the symbol for the center parts of the border.
	 *
	 * @return String The symbol for the center parts of the border
	 */
	public function borderSymbolCenter(): String
	{
		return $this->borderSymbolCenter;
	}

	/**
	 * Returns the symbol for the end of the border.
	 *
	 * @return String The symbol for the end of the border
	 */
	public function borderSymbolEnd(): String
	{
		return $this->borderSymbolEnd;
	}

	/**
	 * Returns the start coordinate of this border.
	 *
	 * @return Coordinate The start coordinate of this border
	 */
	public function startsAt(): Coordinate
	{
		return $this->startsAt;
	}

	/**
	 * Returns the end coordinate of this border
	 *
	 * @return Coordinate The end coordinate of this border
	 */
	public function endsAt(): Coordinate
	{
		return $this->endsAt;
	}


	// Class Methods

	/**
	 * Initializes the border symbols of this border.
	 * The various border symbols and start/end position of this border must be set before this method is called.
	 */
    private function initializeBorderSymbols()
    {
	    $borderSymbols = array();

	    $borderSymbols[] = $this->borderSymbolStart;
	    $borderSymbols = array_merge($borderSymbols, array_pad(array(), $this->getBorderLength(), $this->borderSymbolCenter));
	    $borderSymbols[] = $this->borderSymbolEnd;

	    $this->borderSymbols = $borderSymbols;
    }

	/**
	 * Calculates and returns the length of this border without start/end symbols.
	 *
	 * @return int The length of this border without start/end symbols
	 */
    abstract protected function getBorderLength(): int;

	/**
	 * Returns the position at which this border collides with a border or null if the borders don't collide.
	 *
	 * @param OutputBorderPart $_border The border
	 *
	 * @return int|null The position at which this border collides with a border or null if the borders don't collide
	 */
	abstract protected function collidesWith(OutputBorderPart $_border);

	/**
	 * Checks whether a border collides with this border and changes the border symbols accordingly.
	 *
	 * @param OutputBorderPart $_border The border
	 */
	public function collideWith(OutputBorderPart $_border)
	{
		$borderCollisionPosition = $this->collidesWith($_border);
		if ($borderCollisionPosition !== null)
		{
			if ($_border instanceof BaseOuterBorderPartBuilder) $this->collideWithOuterBorderAt($borderCollisionPosition);
			elseif ($_border instanceof BaseInnerBorderPartBuilder) $this->collideWithInnerBorderAt($borderCollisionPosition);
		}

		// TODO: Fix for the case that border parts overlap
		// TODO: Adjust function names to border parts
	}

	/**
	 * Handles a collision with an inner border at a specific position.
	 *
	 * @param int $_position The collision position in this border
	 */
	protected function collideWithInnerBorderAt(int $_position)
    {
    	if ($_position == 0) $collisionSymbol = $this->borderSymbolInnerBorderCollisionStart;
    	elseif ($_position == $this->getBorderLength()) $collisionSymbol = $this->borderSymbolInnerBorderCollisionEnd;
    	else $collisionSymbol = $this->borderSymbolInnerBorderCollisionCenter;

    	$this->borderSymbols[$_position] = $collisionSymbol;
    }

	/**
	 * Handles a collision with an outer border at a specific position.
	 *
	 * @param int $_position The collision position in this border
	 */
	protected function collideWithOuterBorderAt(int $_position)
    {
	    if ($_position == 0) $collisionSymbol = $this->borderSymbolOuterBorderCollisionStart;
	    elseif ($_position == count($this->borderSymbols)) $collisionSymbol = $this->borderSymbolOuterBorderCollisionEnd;
	    else $collisionSymbol = $this->borderSymbolOuterBorderCollisionCenter;

	    $this->borderSymbols[$_position] = $collisionSymbol;
    }

	/**
	 * Adds the border symbols of this border to a border symbol grid.
	 *
	 * @param BorderSymbolGrid $_borderSymbolGrid The border symbol grid
	 */
    abstract public function addBorderSymbolsToBorderSymbolGrid(BorderSymbolGrid $_borderSymbolGrid);
}