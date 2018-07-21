<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardPrinter\OutputBoard;


use GameOfLife\Coordinate;

class TextBorderPart
{
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
	 * The border collision symbols inside this border
	 *
	 * @var String[] $borderCollisionSymbols
	 */
	protected $borderCollisionSymbols;


	/**
	 * TextBorderPart constructor.
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
		$this->borderSymbolStart = $_borderSymbolStart;
		$this->borderSymbolCenter = $_borderSymbolCenter;
		$this->borderSymbolEnd = $_borderSymbolEnd;
		$this->borderSymbolOuterBorderCollisionStart = $_borderSymbolOuterBorderCollisionStart;
		$this->borderSymbolOuterBorderCollisionCenter = $_borderSymbolOuterBorderCollisionCenter;
		$this->borderSymbolOuterBorderCollisionEnd = $_borderSymbolOuterBorderCollisionEnd;
		$this->borderSymbolInnerBorderCollisionStart = $_borderSymbolInnerBorderCollisionStart;
		$this->borderSymbolInnerBorderCollisionCenter = $_borderSymbolInnerBorderCollisionCenter;
		$this->borderSymbolInnerBorderCollisionEnd = $_borderSymbolInnerBorderCollisionEnd;

		$this->borderCollisionSymbols = array();
	}


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
	 * Handles a collision with an inner border at a specific position.
	 *
	 * @param int $_position The collision position in this border
	 */
	protected function collideWithInnerBorderAt(int $_position)
	{
		if ($_position == 0) $collisionSymbol = $this->borderSymbolInnerBorderCollisionStart;
		elseif ($_position == $this->getTotalBorderLength()) $collisionSymbol = $this->borderSymbolInnerBorderCollisionEnd;
		else $collisionSymbol = $this->borderSymbolInnerBorderCollisionCenter;

		$this->borderCollisionSymbols[$_position] = $collisionSymbol;
	}

	/**
	 * Handles a collision with an outer border at a specific position.
	 *
	 * @param int $_position The collision position in this border
	 */
	protected function collideWithOuterBorderAt(int $_position)
	{
		if ($_position == 0) $collisionSymbol = $this->borderSymbolOuterBorderCollisionStart;
		elseif ($_position == $this->getTotalBorderLength()) $collisionSymbol = $this->borderSymbolOuterBorderCollisionEnd;
		else $collisionSymbol = $this->borderSymbolOuterBorderCollisionCenter;

		$this->borderCollisionSymbols[$_position] = $collisionSymbol;
	}

	protected function collide()
	{
		// Foreach borders
		if ($_borderPart instanceof BaseOuterBorder) $this->collideWithOuterBorderAt($borderCollisionPosition);
		elseif ($_borderPart instanceof BaseInnerBorder) $this->collideWithInnerBorderAt($borderCollisionPosition);
	}

	/**
	 * Adds the border symbols of this border to a border symbol grid.
	 *
	 * @param BaseSymbolGrid $_borderSymbolGrid The border symbol grid
	 */
	abstract public function addBorderSymbolsToBorderSymbolGrid(BaseSymbolGrid $_borderSymbolGrid);




	// Inner border stuff

    /**
     * The symbol that will be placed in the top outer border when this border collides with it
     *
     * @var String $borderSymbolCollisionTopOuterBorder
     */
    protected $borderSymbolCollisionTopOuterBorder;

    /**
     * The symbol that will be placed in the bottom outer border when this border collides with it
     *
     * @var String $borderSymbolCollisionBottomOuterBorder
     */
    protected $borderSymbolCollisionBottomOuterBorder;

    /**
     * The symbol that will be placed in the left outer border when this border collides with it
     *
     * @var String $borderSymbolCollisionLeftOuterBorder
     */
    protected $borderSymbolCollisionLeftOuterBorder;

    /**
     * The symbol that will be placed in the right outer border when this border collides with it
     *
     * @var String $borderSymbolCollisionRightOuterBorder
     */
    protected $borderSymbolCollisionRightOuterBorder;


    // Magic Methods

    /**
     * BaseInnerBorderPrinter constructor.
     *
     * @param String $_borderSymbolTopLeft The symbol for the top left corner of the border
     * @param String $_borderSymbolTopRight The symbol for the top right corner of the border
     * @param String $_borderSymbolBottomLeft The symbol for the bottom left corner of the border
     * @param String $_borderSymbolBottomRight The symbol for the bottom right corner of the border
     * @param String $_borderSymbolTopBottom The symbol for the top and bottom border
     * @param String $_borderSymbolLeftRight The symbol for the left an right border
     * @param String $_borderSymbolCollisionTopOuterBorder The symbol that will be placed in the top outer border when this border collides with it
     * @param String $_borderSymbolCollisionBottomOuterBorder The symbol that will be placed in the bottom outer border when this border collides with it
     * @param String $_borderSymbolCollisionLeftOuterBorder The symbol that will be placed in the left outer border when this border collides with it
     * @param String $_borderSymbolCollisionRightOuterBorder The symbol that will be placed in the right outer border when this border collides with it
     */
    protected function __construct(String $_borderSymbolTopLeft, String $_borderSymbolTopRight, String $_borderSymbolBottomLeft, String $_borderSymbolBottomRight, String $_borderSymbolTopBottom, String $_borderSymbolLeftRight, String $_borderSymbolCollisionTopOuterBorder, String $_borderSymbolCollisionBottomOuterBorder, String $_borderSymbolCollisionLeftOuterBorder, String $_borderSymbolCollisionRightOuterBorder)
    {
        parent::__construct($_borderSymbolTopLeft, $_borderSymbolTopRight, $_borderSymbolBottomLeft, $_borderSymbolBottomRight, $_borderSymbolTopBottom, $_borderSymbolLeftRight);

        $this->borderSymbolCollisionTopOuterBorder = $_borderSymbolCollisionTopOuterBorder;
        $this->borderSymbolCollisionBottomOuterBorder = $_borderSymbolCollisionBottomOuterBorder;
        $this->borderSymbolCollisionLeftOuterBorder = $_borderSymbolCollisionLeftOuterBorder;
        $this->borderSymbolCollisionRightOuterBorder = $_borderSymbolCollisionRightOuterBorder;
    }
}