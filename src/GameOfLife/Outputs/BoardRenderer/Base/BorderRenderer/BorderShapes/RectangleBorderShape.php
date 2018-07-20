<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Base\BorderShapes;

use GameOfLife\Coordinate;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\HorizontalBorderPart;
use Output\BoardPrinter\OutputBoard\OutputBorderPart\VerticalBorderPart;
use Output\BoardRenderer\Base\BaseBorderPart;
use Output\BoardRenderer\Base\BaseBorderRenderer;

/**
 * Creates border parts that form a rectangle.
 */
abstract class RectangleBorderShape extends BaseBorderShape
{
	// Attributes

	/**
	 * The top left corner coordinate of this border shape
	 *
	 * @var Coordinate $topLeftCornerCoordinate
	 */
	private $topLeftCornerCoordinate;

	/**
	 * The bottom right corner coordinate of this border shape
	 *
	 * @var Coordinate $bottomRightCornerCoordinate
	 */
	private $bottomRightCornerCoordinate;


	// Magic Methods

	/**
	 * RectangleBorderShape constructor.
	 *
	 * @param Coordinate $_topLeftCornerCoordinate The top left corner coordinate of this border shape
	 * @param Coordinate $_bottomRightCornerCoordinate The bottom right corner coordinate of this border shape
	 */
	protected function __construct(Coordinate $_topLeftCornerCoordinate, Coordinate $_bottomRightCornerCoordinate)
	{
		$this->topLeftCornerCoordinate = $_topLeftCornerCoordinate;
		$this->bottomRightCornerCoordinate = $_bottomRightCornerCoordinate;
	}


	// Class Methods

	/**
	 * Adds all borders of this border part builder to a border renderer.
	 *
	 * @param BaseBorderRenderer $_borderRenderer The border renderer
	 */
	public function addBorderPartsToBorderRenderer(BaseBorderRenderer $_borderRenderer)
	{
		$_borderRenderer->addBorderPart($this->getTopBorderPart());
		$_borderRenderer->addBorderPart($this->getBottomBorderPart());
		$_borderRenderer->addBorderPart($this->getLeftBorderPart());
		$_borderRenderer->addBorderPart($this->getRightBorderPart());
	}

	/**
	 * Generates and returns the top border part of this border shape.
	 *
	 * @return HorizontalBorderPart The top border part of this border shape
	 */
	abstract protected function getTopBorderPart(): HorizontalBorderPart;

	/**
	 * Generates and returns the bottom border part of this border shape.
	 *
	 * @return HorizontalBorderPart The bottom border part of this border shape
	 */
	abstract protected function getBottomBorderPart():HorizontalBorderPart;

	/**
	 * Generates and returns the left border part of this border shape.
	 *
	 * @return VerticalBorderPart The left border part of this border shape
	 */
	abstract protected function getLeftBorderPart(): VerticalBorderPart;

	/**
	 * Generates and returns the right border part of this border shape.
	 *
	 * @return VerticalBorderPart The right border part of this border shape
	 */
	abstract protected function getRightBorderPart(): VerticalBorderPart;
}
