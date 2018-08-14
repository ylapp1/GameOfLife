<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Base\Border\BorderPart;

use GameOfLife\Coordinate;
use BoardRenderer\Base\Border\BaseBorder;
use BoardRenderer\Base\Border\BorderPart\Shapes\BaseBorderPartShape;

/**
 * Container class that stores information about a border part.
 * A border part is a single line of the total border with a fixed start and end point, for example horizontal, vertical,
 * diagonal or curved lines.
 */
abstract class BaseBorderPart
{
	// Attributes

	/**
	 * The start coordinate
	 *
	 * @var Coordinate $startsAt
	 */
	protected $startsAt;

	/**
	 * The end coordinate
	 *
	 * @var Coordinate $endsAt
	 */
	protected $endsAt;

	/**
	 * The shape
	 *
	 * @var BaseBorderPartShape $shape
	 */
	protected $shape;

    /**
     * The parent border
     *
     * @var BaseBorder $parentBorder
     */
	protected $parentBorder;


	// Magic Methods

	/**
	 * BaseBorderPart constructor.
	 *
     * @param BaseBorder $_parentBorder The parent border
	 * @param Coordinate $_startsAt The start coordinate of this border part
	 * @param Coordinate $_endsAt The end coordinate of this border part
     * @param BaseBorderPartShape $_shape The shape of this border part
	 */
	protected function __construct($_parentBorder, Coordinate $_startsAt, Coordinate $_endsAt, $_shape)
    {
        $this->parentBorder = $_parentBorder;
    	$this->startsAt = $_startsAt;
    	$this->endsAt = $_endsAt;
    	$this->shape = $_shape;
    	$this->shape->setParentBorderPart($this);
    }


    // Getters and Setters

	/**
	 * Returns the start coordinate.
	 *
	 * @return Coordinate The start coordinate
	 */
	public function startsAt(): Coordinate
	{
		return $this->startsAt;
	}

	/**
	 * Returns the end coordinate.
	 *
	 * @return Coordinate The end coordinate
	 */
	public function endsAt(): Coordinate
	{
		return $this->endsAt;
	}

    /**
     * Returns the parent border.
     *
     * @return BaseBorder The parent border
     */
	public function parentBorder()
    {
        return $this->parentBorder;
    }

	/**
	 * Returns the border part shape.
	 *
	 * @return BaseBorderPartShape The border part shape
	 */
    public function shape()
    {
    	return $this->shape;
    }


	// Class Methods

	/**
	 * Creates and returns the rendered border part.
	 *
	 * @return mixed The rendered border part
	 */
	public function getRenderedBorderPart()
    {
    	return $this->shape->getRenderedBorderPart();
    }
}
