<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard\OutputBorder;

use GameOfLife\Coordinate;
use Output\BoardPrinter\Border\InnerBorder\BaseInnerBorderPartBuilder;
use Output\BoardPrinter\Border\OuterBorder\BaseOuterBorderPartBuilder;
use Output\BoardPrinter\OutputBoard\SymbolGrid\BorderSymbolGrid;

/**
 * Parent class for output borders.
 */
abstract class OutputBorderPart
{
	private $borderStartCoordinate;
    protected $borderSymbols;

    private $borderSymbolOuterBorderCollisionStart;
    private $borderSymbolOuterBorderCollisionCenter;
    private $borderSymbolOuterBorderCollisionEnd;

	private $borderSymbolInnerBorderCollisionStart;
	private $borderSymbolInnerBorderCollisionCenter;
	private $borderSymbolInnerBorderCollisionEnd;

	/**
	 * The start coordinate of this border
	 *
	 * @var Coordinate $startsAt
	 */
	protected $startsAt;

	/**
	 * The end coordinate of this border
	 *
	 * @var Coordinate
	 */
	protected $endsAt;

	protected function __construct(Coordinate $_startsAt, Coordinate $_endsAt, array $_borderSymbols)
    {
    	$this->startsAt = $_startsAt;
    	$this->endsAt = $_endsAt;
    	$this->borderSymbols = $_borderSymbols;
    }


    public function startsAt()
    {
    	return $this->startsAt;
    }

    public function endsAt()
    {
    	return $this->endsAt;
    }


	abstract protected function collidesWith(OutputBorderPart $_border): int;

	public function collideWith(OutputBorderPart $_border)
	{
		$borderCollisionPosition = $this->collidesWith($_border);
		if ($borderCollisionPosition !== null)
		{
			if ($_border instanceof BaseOuterBorderPartBuilder) $this->collideWithOuterBorderAt($borderCollisionPosition);
			elseif ($_border instanceof BaseInnerBorderPartBuilder) $this->collideWithInnerBorderAt($borderCollisionPosition);
		}
	}

	protected function collideWithInnerBorderAt(int $_position)
    {
    	if ($_position == 0) $collisionSymbol = $this->borderSymbolInnerBorderCollisionStart;
    	elseif ($_position == count($this->borderSymbols)) $collisionSymbol = $this->borderSymbolInnerBorderCollisionEnd;
    	else $collisionSymbol = $this->borderSymbolInnerBorderCollisionCenter;

    	$this->borderSymbols[$_position] = $collisionSymbol;
    }

	protected function collideWithOuterBorderAt(int $_position)
    {
	    if ($_position == 0) $collisionSymbol = $this->borderSymbolOuterBorderCollisionStart;
	    elseif ($_position == count($this->borderSymbols)) $collisionSymbol = $this->borderSymbolOuterBorderCollisionEnd;
	    else $collisionSymbol = $this->borderSymbolOuterBorderCollisionCenter;

	    $this->borderSymbols[$_position] = $collisionSymbol;
    }

    abstract public function addBorderSymbolsToBorderSymbolGrid(BorderSymbolGrid $_borderSymbolGrid);
}
